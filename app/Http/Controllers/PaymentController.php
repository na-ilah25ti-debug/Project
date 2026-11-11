<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['periode' => ['nullable', 'date_format:Y-m']]);
        $periode = $filters['periode'] ?? now()->format('Y-m');

        $payments = Payment::with('tenant.room.house')
            ->where('periode', $periode)
            ->orderByRaw("case status when 'menunggu' then 0 when 'belum' then 1 else 2 end")
            ->orderBy('jatuh_tempo')
            ->get();

        return view('payments.index', [
            'payments' => $payments,
            'periode' => $periode,
            'terkumpul' => $payments->where('status', 'lunas')->sum('jumlah'),
            'belumBayar' => $payments->where('status', 'belum')->sum('jumlah'),
            'verifikasi' => $payments->where('status', 'menunggu')->count(),
        ]);
    }

    // B2/B4: buat tagihan bulanan untuk semua penghuni aktif
    public function generate(Request $request)
    {
        $periode = $request->validate(['periode' => ['required', 'date_format:Y-m']])['periode'];
        $dibuat = 0;

        foreach (Tenant::with('room')->where('aktif', true)->get() as $tenant) {
            if (! $tenant->room) {
                continue;
            }

            $hari = min($tenant->tanggal_masuk->day, 28);
            $payment = Payment::firstOrCreate(
                ['tenant_id' => $tenant->id, 'periode' => $periode],
                [
                    'jumlah' => $tenant->room->harga,
                    'jatuh_tempo' => Carbon::createFromFormat('Y-m-d', $periode . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT)),
                ]
            );

            if ($payment->wasRecentlyCreated) {
                $dibuat++;
            }
        }

        return redirect()->route('tagihan.index', ['periode' => $periode])->with('ok', "$dibuat tagihan baru dibuat.");
    }

    public function lunas(Payment $payment)
    {
        $changed = Payment::whereKey($payment->id)
            ->where('status', $payment->status)
            ->where('status', '!=', 'lunas')
            ->where('bukti', $payment->bukti)
            ->update(['status' => 'lunas', 'tanggal_bayar' => today()]);

        return back()->with('ok', $changed ? 'Pembayaran dicatat lunas.' : 'Status tagihan sudah berubah.');
    }

    // Bukti ditolak: tagihan kembali ke "belum" agar penghuni bisa mengunggah ulang
    public function tolak(Payment $payment)
    {
        $changed = Payment::whereKey($payment->id)
            ->where('status', 'menunggu')
            ->where('bukti', $payment->bukti)
            ->update(['status' => 'belum', 'bukti' => null, 'metode' => null, 'tanggal_bayar' => null]);

        if (! $changed) {
            return back()->withErrors(['bukti' => 'Hanya bukti yang menunggu verifikasi yang dapat ditolak.']);
        }

        if ($payment->bukti) {
            Storage::disk('local')->delete($payment->bukti);
        }

        return back()->with('ok', 'Bukti ditolak, tagihan kembali berstatus belum dibayar.');
    }

    // Bukti pembayaran bersifat privat: hanya pemilik dan penghuni yang bersangkutan
    public function bukti(Payment $payment)
    {
        $user = auth()->user();
        $milikSendiri = $payment->tenant && (int) $payment->tenant->user_id === (int) $user->id;
        abort_unless($user->role === 'pemilik' || $milikSendiri, 403);
        abort_unless($payment->bukti && Storage::disk('local')->exists($payment->bukti), 404);

        return Storage::disk('local')->response($payment->bukti, headers: [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
