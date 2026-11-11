<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\GalonOrder;
use App\Models\House;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Photo;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KosWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_shows_three_properties_and_filters_rooms(): void
    {
        $this->seed();

        $this->assertEquals([8, 6, 10], House::withCount('rooms')->orderBy('id')->pluck('rooms_count')->all());
        $this->get('/?jenis=putri&status=kosong&maks=500000')->assertOk()
            ->assertViewHas('rooms', fn ($rooms) => $rooms->total() === 6
                && $rooms->every(fn ($room) => $room->house->jenis === 'putri' && $room->status === 'kosong'))
            ->assertDontSee('/admin/masuk');
    }

    public function test_owner_login_and_registration_enforce_roles(): void
    {
        $owner = User::factory()->create(['role' => 'pemilik']);
        $this->get('/login')->assertOk()->assertDontSee('/admin/masuk');
        $this->get('/register')->assertOk()->assertDontSee('/admin/masuk');

        $this->post('/login', ['email' => $owner->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/admin/masuk', ['email' => $owner->email, 'password' => 'password'])->assertRedirect('/admin/dashboard');
        $this->get('/admin/dashboard')->assertOk();
        $this->get('/anak')->assertForbidden();
        $this->post('/logout');

        $this->post('/register', [
            'name' => 'Penghuni',
            'email' => 'penghuni@example.test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'pemilik',
        ])->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'penghuni@example.test', 'role' => 'penghuni']);
        $this->get('/admin/dashboard')->assertForbidden();
        $this->post('/logout');
        $this->post('/admin/masuk', ['email' => 'penghuni@example.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_public_messages_include_room_context_and_can_be_seen_by_owner(): void
    {
        $this->seed();
        $room = Room::firstOrFail();
        $owner = User::factory()->create(['role' => 'pemilik']);

        $this->from(route('kamar.detail', $room))->post('/pesan', [
            'nama' => 'Calon penghuni',
            'kontak' => '081234567890',
            'isi' => 'Bisa survei hari Sabtu?',
            'room_id' => $room->id,
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('messages', ['kontak' => '6281234567890', 'user_id' => null]);
        $this->actingAs($owner)->get('/admin/pesan')->assertOk()->assertSee($room->label)
            ->assertSee('Bisa survei hari Sabtu?')->assertSee('https://wa.me/6281234567890');
    }

    public function test_linked_tenant_can_submit_payment_report_and_galon_for_owner_review(): void
    {
        Storage::fake('local');
        $this->seed();
        $owner = User::factory()->create(['role' => 'pemilik']);
        $tenantUser = User::factory()->create(['role' => 'penghuni']);
        $otherUser = User::factory()->create(['role' => 'penghuni']);
        $room = Room::firstOrFail();
        $period = today()->format('Y-m');

        $this->actingAs($owner)->post('/admin/penghuni', [
            'room_id' => $room->id,
            'nama' => $tenantUser->name,
            'email' => $tenantUser->email,
            'no_hp' => '081234567890',
            'tanggal_masuk' => today()->toDateString(),
        ])->assertSessionHasNoErrors();
        $this->assertSame('terisi', $room->fresh()->status);
        $this->post('/admin/tagihan/buat', ['periode' => $period])->assertSessionHasNoErrors();
        $this->post('/admin/tagihan/buat', ['periode' => $period])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('payments', 1);
        $payment = Payment::firstOrFail();
        $payment->update(['jatuh_tempo' => today()]);
        $this->assertFalse($payment->terlambat);

        $this->actingAs($tenantUser)->get('/anak')->assertOk()->assertSee($room->label);
        $this->get('/anak/tagihan')->assertOk()->assertSee('Ajukan pembayaran');
        $this->post(route('anak.bayar', $payment), [
            'metode' => 'Transfer bank',
            'bukti' => UploadedFile::fake()->image('bukti.png'),
        ])->assertSessionHasNoErrors();
        $payment->refresh();
        $this->assertSame('menunggu', $payment->status);
        Storage::disk('local')->assertExists($payment->bukti);
        $this->get(route('bukti.show', $payment))->assertOk();
        $this->post('/anak/laporan', ['judul' => 'Keran bocor'])->assertSessionHasNoErrors();
        $this->post('/anak/galon', ['jumlah' => 2])->assertSessionHasNoErrors();
        $this->post('/anak/pesan', ['isi' => 'Mohon cek keran.'])->assertSessionHasNoErrors();

        $this->actingAs($otherUser)->get(route('bukti.show', $payment))->assertForbidden();
        $this->post(route('anak.bayar', $payment), ['metode' => 'Transfer bank'])->assertForbidden();

        $this->actingAs($owner)->get('/admin/tagihan')->assertOk()->assertSee('Menunggu verifikasi');
        $this->patch(route('tagihan.lunas', $payment))->assertSessionHasNoErrors();
        $this->patch(route('tagihan.tolak', $payment))->assertSessionHasErrors('bukti');
        $this->assertSame('lunas', $payment->fresh()->status);
        $this->get('/admin/laporan')->assertOk()->assertSee('Keran bocor');
        $this->patch(route('laporan.update', Complaint::firstOrFail()), ['status' => 'diproses'])->assertSessionHasNoErrors();
        $this->get('/admin/galon')->assertOk()->assertSee($tenantUser->name);
        $this->patch(route('galon.update', GalonOrder::firstOrFail()), ['status' => 'diantar'])->assertSessionHasNoErrors();
        $this->patch(route('pesan.balas', Message::firstOrFail()), ['balasan' => 'Akan segera dicek.'])->assertSessionHasNoErrors();

        $this->actingAs($tenantUser)->get('/anak/tagihan')->assertOk()->assertSee('Lunas');
        $this->get('/anak/laporan')->assertOk()->assertSee('Diproses');
        $this->get('/anak/galon')->assertOk()->assertSee('Diantar');
        $this->get('/anak/pesan')->assertOk()->assertSee('Akan segera dicek.');
    }

    public function test_owner_can_publish_room_photos_from_the_upload_form(): void
    {
        Storage::fake('public');
        $this->seed();
        $owner = User::factory()->create(['role' => 'pemilik']);
        $room = Room::firstOrFail();

        $this->actingAs($owner)->post('/admin/foto', [
            'jenis' => 'kamar',
            'room_id' => $room->id,
            'foto' => [UploadedFile::fake()->image('kamar.png')],
        ])->assertSessionHasNoErrors();

        $photo = Photo::firstOrFail();
        $this->post('/logout');
        $this->get(route('kamar.detail', $room))->assertOk()->assertSee($photo->url);
        $this->get($photo->url)->assertOk()->assertHeader('content-type', 'image/png');
    }
}
