@extends('layouts.kos')
@section('title', 'Tagihan · KosKita')
@section('content')
<h1>Tagihan &amp; bayar</h1>
@if (! $t)
    <div class="card">Tagihan tersedia setelah akunmu dihubungkan ke kamar aktif. <a href="{{ route('anak.pesan') }}">Hubungi pemilik</a>.</div>
@else
    <div class="card portal-callout">
        <strong>Transfer dan unggah bukti</strong>
        @if (config('kos.rekening'))
            <p>Transfer ke {{ config('kos.rekening') }}, lalu unggah bukti pada tagihan yang sesuai. Pemilik akan memeriksa bukti dan memperbarui status.</p>
        @else
            <p><a href="{{ route('anak.pesan') }}">Minta instruksi pembayaran ke pemilik</a>, lalu unggah bukti pada tagihan yang sesuai.</p>
        @endif
    </div>
    <div class="payment-list">
        @forelse ($payments as $payment)
            @php
                $status = $payment->terlambat ? 'Terlambat' : ['belum' => 'Belum dibayar', 'menunggu' => 'Menunggu verifikasi', 'lunas' => 'Lunas'][$payment->status];
            @endphp
            <article class="card payment-line">
                <div>
                    <span class="chip {{ $payment->terlambat ? 'b-telat' : 'b-' . $payment->status }}">{{ $status }}</span>
                    <h3>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</h3>
                    <p class="mu">Periode {{ $payment->periode }} · Jatuh tempo {{ $payment->jatuh_tempo->format('d/m/Y') }}</p>
                    @if ($payment->tanggal_bayar)<p class="mu">Dibayar {{ $payment->tanggal_bayar->format('d/m/Y') }}</p>@endif
                </div>
                @if ($payment->status === 'belum')
                    <form method="POST" action="{{ route('anak.bayar', $payment) }}" enctype="multipart/form-data">
                        @csrf
                        <label>Metode
                            <select name="metode" required><option>Transfer bank</option><option>QRIS</option><option>E-wallet</option></select>
                        </label>
                        <label>Bukti pembayaran <small>(JPG, PNG, atau PDF · maks. 4 MB)</small>
                            <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" required>
                        </label>
                        <button class="btn sm" type="submit">Ajukan pembayaran</button>
                    </form>
                @elseif ($payment->bukti)
                    <div><a class="text-link" href="{{ route('bukti.show', $payment) }}" target="_blank" rel="noopener">Lihat bukti <x-kos-icon name="external" :width="15" :height="15" /></a></div>
                @endif
            </article>
        @empty
            <div class="card mu">Belum ada tagihan.</div>
        @endforelse
    </div>
@endif
@endsection
