<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\GalonOrder;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// Halaman untuk anak kos (penghuni yang sudah punya akun)
class AnakController extends Controller
{
    private function tenant(): ?Tenant
    {
        return Tenant::with('room.house')
            ->where('user_id', auth()->id())
            ->where('aktif', true)
            ->first();
    }

    public function home()
    {
        $t = $this->tenant();
        $tagihan = $t?->payments()->whereIn('status', ['belum', 'menunggu'])->orderBy('jatuh_tempo')->first();

        $stat = ['tagihan' => 0, 'galon' => 0, 'laporan' => 0, 'pesan' => 0];
        $notif = collect();

        if ($t) {
            $stat['tagihan'] = $t->payments()->where('status', 'belum')->count();
            $stat['galon'] = GalonOrder::where('tenant_id', $t->id)->whereIn('status', ['dipesan', 'diantar'])->count();
            $stat['laporan'] = Complaint::where('tenant_id', $t->id)->where('status', '!=', 'selesai')->count();

            if ($tagihan) {
                $sisa = (int) now()->startOfDay()->diffInDays($tagihan->jatuh_tempo, false);
                $teks = $tagihan->status === 'menunggu'
                    ? 'Bukti pembayaran sedang diverifikasi ibu kos.'
                    : ($sisa < 0 ? 'Terlambat ' . abs($sisa) . ' hari.' : ($sisa === 0 ? 'Jatuh tempo hari ini.' : $sisa . ' hari lagi sebelum jatuh tempo.'));
                $notif->push(['judul' => 'Tagihan ' . $tagihan->periode, 'isi' => $teks, 'waktu' => $tagihan->updated_at]);
            }
            foreach (Complaint::where('tenant_id', $t->id)->where('status', '!=', 'diterima')->latest('updated_at')->take(2)->get() as $c) {
                $notif->push(['judul' => 'Laporan "' . $c->judul . '" ' . $c->status, 'isi' => '', 'waktu' => $c->updated_at]);
            }
            foreach (GalonOrder::where('tenant_id', $t->id)->whereIn('status', ['diantar', 'selesai'])->latest('updated_at')->take(2)->get() as $g) {
                $notif->push(['judul' => 'Pesanan galon ' . $g->status, 'isi' => $g->jumlah . ' galon', 'waktu' => $g->updated_at]);
            }
        }

        $stat['pesan'] = Message::where('user_id', auth()->id())->whereNull('balasan')->count();
        foreach (Message::where('user_id', auth()->id())->whereNotNull('balasan')->latest('balasan_at')->take(2)->get() as $m) {
            $notif->push(['judul' => 'Ibu kos membalas pesanmu', 'isi' => \Illuminate\Support\Str::limit($m->balasan, 80), 'waktu' => $m->balasan_at]);
        }

        $notif = $notif->sortByDesc('waktu')->take(5)->values();

        return view('anak.home', compact('t', 'tagihan', 'stat', 'notif'));
    }

    public function tagihan()
    {
        $t = $this->tenant();
        $payments = $t ? $t->payments()->orderByDesc('periode')->get() : collect();

        return view('anak.tagihan', compact('t', 'payments'));
    }

    // Bayar di website: pilih metode dan unggah bukti, lalu ibu kos memverifikasi
    public function bayar(Request $request, Payment $payment)
    {
        $t = $this->tenant();
        abort_unless($t && (int) $payment->tenant_id === (int) $t->id, 403);

        if ($payment->status !== 'belum') {
            return back()->withErrors(['bayar' => 'Tagihan ini sudah dibayar atau sedang diverifikasi.']);
        }

        $data = $request->validate([
            'metode' => ['required', 'in:Transfer bank,QRIS,E-wallet'],
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $path = $request->file('bukti')->store('bukti', 'local');
        if (! $path) {
            return back()->withErrors(['bukti' => 'Bukti pembayaran gagal disimpan. Coba lagi.']);
        }

        try {
            $changed = Payment::whereKey($payment->id)->where('status', 'belum')->update([
                'status' => 'menunggu',
                'metode' => $data['metode'],
                'bukti' => $path,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);

            throw $exception;
        }

        if (! $changed) {
            Storage::disk('local')->delete($path);

            return back()->withErrors(['bayar' => 'Status tagihan sudah berubah. Periksa tagihan sebelum mengirim ulang.']);
        }

        return back()->with('ok', 'Bukti pembayaran terkirim. Menunggu verifikasi ibu kos.');
    }

    public function galon()
    {
        $t = $this->tenant();
        $orders = $t ? GalonOrder::where('tenant_id', $t->id)->latest()->get() : collect();

        return view('anak.galon', compact('t', 'orders'));
    }

    public function pesanGalon(Request $request)
    {
        $t = $this->tenant();
        abort_unless($t, 403);

        $data = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:10'],
            'catatan' => ['nullable', 'string', 'max:150'],
        ]);

        GalonOrder::create($data + ['tenant_id' => $t->id, 'harga_satuan' => config('kos.harga_galon')]);

        return back()->with('ok', 'Pesanan galon dikirim ke ibu kos.');
    }

    public function laporan()
    {
        $t = $this->tenant();
        $complaints = $t ? Complaint::where('tenant_id', $t->id)->latest()->get() : collect();

        return view('anak.laporan', compact('t', 'complaints'));
    }

    public function kirimLaporan(Request $request)
    {
        $t = $this->tenant();
        abort_unless($t && $t->room_id, 403);

        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);

        Complaint::create($data + ['room_id' => $t->room_id, 'tenant_id' => $t->id]);

        return back()->with('ok', 'Laporan terkirim. Kamu bisa memantau statusnya di sini.');
    }

    public function pesan()
    {
        $t = $this->tenant();
        $messages = Message::where('user_id', auth()->id())->latest()->get();

        return view('anak.pesan', compact('t', 'messages'));
    }

    public function kirimPesan(Request $request)
    {
        $data = $request->validate(['isi' => ['required', 'string', 'max:1000']]);

        Message::create([
            'user_id' => auth()->id(),
            'nama' => auth()->user()->name,
            'kontak' => $this->tenant()?->no_hp,
            'isi' => $data['isi'],
        ]);

        return back()->with('ok', 'Pesan terkirim ke ibu kos.');
    }
}
