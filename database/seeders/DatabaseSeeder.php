<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\House;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'pemilik@kos.test'], [
            'name' => 'Pemilik Kos',
            'password' => bcrypt('password'),
            'role' => 'pemilik',
        ]);

        $umum = 'Parkir, CCTV';
        $saka = House::firstOrCreate(['nama' => 'Saka Kost'], ['jenis' => 'putri', 'fasilitas_umum' => $umum]);
        $syariah = House::firstOrCreate(['nama' => 'Kos Syariah'], ['jenis' => 'putri', 'fasilitas_umum' => $umum]);
        $cowok = House::firstOrCreate(['nama' => 'Kos Cowok'], ['jenis' => 'putra', 'fasilitas_umum' => $umum]);

        // Saka Kost: 8 kamar (4 kipas Rp700 ribu, 4 AC Rp1 juta)
        $sisa = 'kasur, lemari, WC dalam, listrik token, WiFi, dapur bersama';
        for ($i = 1; $i <= 8; $i++) {
            $ac = $i > 4;
            Room::firstOrCreate(['house_id' => $saka->id, 'nomor' => sprintf('%02d', $i)], [
                'tipe' => $ac ? 'AC' : 'Kipas',
                'harga' => $ac ? 1000000 : 700000,
                'fasilitas' => ($ac ? 'AC, ' : 'Kipas, ') . $sisa,
            ]);
        }

        // Kos Syariah: 6 kamar (Rp400 ribu - Rp500 ribu)
        for ($i = 1; $i <= 6; $i++) {
            Room::firstOrCreate(['house_id' => $syariah->id, 'nomor' => sprintf('%02d', $i)], [
                'tipe' => 'Kipas',
                'harga' => $i <= 3 ? 400000 : 500000,
                'fasilitas' => 'Kipas, kasur, lemari, WC luar/bersama, dapur bersama',
            ]);
        }

        // Kos Cowok: 10 kamar (Rp700 ribu)
        for ($i = 1; $i <= 10; $i++) {
            Room::firstOrCreate(['house_id' => $cowok->id, 'nomor' => sprintf('%02d', $i)], [
                'tipe' => 'Kipas',
                'harga' => 700000,
                'fasilitas' => 'Kasur, lemari, kipas, WC dalam',
            ]);
        }

        // Data contoh penghuni, tagihan, dan laporan
        $r1 = Room::where('house_id', $saka->id)->where('nomor', '01')->first();
        $r2 = Room::where('house_id', $saka->id)->where('nomor', '02')->first();
        $r3 = Room::where('house_id', $cowok->id)->where('nomor', '01')->first();

        // Akun login contoh untuk anak kos: penghuni1@kos.test (dan 2, 3) / password
        $akun = fn (int $n) => User::firstOrCreate(['email' => "penghuni{$n}@kos.test"], [
            'name' => "Contoh Penghuni {$n}",
            'password' => bcrypt('password'),
            'role' => 'penghuni',
        ])->id;

        $t1 = Tenant::firstOrCreate(['nama' => 'Contoh Penghuni 1'], ['user_id' => $akun(1), 'room_id' => $r1->id, 'no_hp' => '081200000001', 'tanggal_masuk' => now()->subMonths(3)->startOfMonth()->addDays(4)]);
        $t2 = Tenant::firstOrCreate(['nama' => 'Contoh Penghuni 2'], ['user_id' => $akun(2), 'room_id' => $r2->id, 'no_hp' => '081200000002', 'tanggal_masuk' => now()->subMonths(2)->startOfMonth()->addDays(9)]);
        $t3 = Tenant::firstOrCreate(['nama' => 'Contoh Penghuni 3'], ['user_id' => $akun(3), 'room_id' => $r3->id, 'no_hp' => '081200000003', 'tanggal_masuk' => now()->subMonth()->startOfMonth()->addDays(2)]);
        foreach ([$r1, $r2, $r3] as $r) {
            $r->update(['status' => 'terisi']);
        }

        Room::where('house_id', $syariah->id)->where('nomor', '06')->update(['status' => 'perawatan']);

        $periode = now()->format('Y-m');
        Payment::firstOrCreate(['tenant_id' => $t1->id, 'periode' => $periode], ['jumlah' => $r1->harga, 'jatuh_tempo' => now()->startOfMonth()->addDays(4), 'status' => 'belum']);
        Payment::firstOrCreate(['tenant_id' => $t2->id, 'periode' => $periode], ['jumlah' => $r2->harga, 'jatuh_tempo' => now()->startOfMonth()->addDays(9), 'status' => 'lunas', 'tanggal_bayar' => now()]);
        Payment::firstOrCreate(['tenant_id' => $t3->id, 'periode' => $periode], ['jumlah' => $r3->harga, 'jatuh_tempo' => now()->startOfMonth()->addDays(2), 'status' => 'belum']);

        Complaint::firstOrCreate(['judul' => 'Keran kamar mandi bocor'], ['room_id' => $r1->id, 'tenant_id' => $t1->id, 'status' => 'diproses']);
    }
}
