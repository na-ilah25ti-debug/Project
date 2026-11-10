@extends('layouts.kos')
@section('title', 'Tagihan - KosKita')
@section('content')
<h1>Tagihan</h1>
@if (! $t)
<div class="card">Akunmu belum terhubung ke kamar.</div>
@else
<div class="card" style="margin:14px 0">
<b>Cara bayar</b>
<p class="mu" style="margin:4px 0 0">Transfer ke: <b style="color:var(--tx)">{{ config('kos.rekening') }}</b>. Setelah transfer, unggah bukti di tagihan yang sesuai. Ibu kos akan memverifikasi, lalu status berubah menjadi Lunas.</p>
</div>
<div class="scroll"><table>
<tr><th>Periode</th><th>Jumlah</th><th>Jatuh tempo</th><th>Status</th><th>Bayar</th></tr>
@forelse ($payments as $p)
@php $sisa = (int) now()->startOfDay()->diffInDays($p->jatuh_tempo, false); @endphp
<tr><td>{{ $p->periode }}</td><td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
<td>{{ $p->jatuh_tempo->format('d/m/Y') }}
@if ($p->status === 'belum')<div class="mu">{{ $sisa < 0 ? 'Terlambat ' . abs($sisa) . ' hari' : ($sisa === 0 ? 'Hari ini' : $sisa . ' hari lagi') }}</div>@endif</td>
<td><span class="chip {{ $p->terlambat ? 'b-telat' : 'b-' . $p->status }}">{{ $p->terlambat ? 'Terlambat' : ucfirst($p->status) }}</span>
@if ($p->status === 'lunas')<div class="mu">{{ $p->tanggal_bayar?->format('d/m/Y') }}</div>@endif</td>
<td>
@if ($p->status === 'belum')
<form method="POST" action="{{ route('anak.bayar', $p) }}" enctype="multipart/form-data">@csrf
<select name="metode" required><option>Transfer bank</option><option>QRIS</option><option>E-wallet</option></select>
<input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" required style="margin-top:6px">
<button class="btn sm" style="margin-top:6px">Kirim bukti</button></form>
@elseif ($p->status === 'menunggu')
<span class="mu">{{ $p->metode }}</span> &middot; <a href="{{ route('bukti.show', $p) }}" target="_blank">lihat bukti</a>
@else <span class="mu">{{ $p->metode ?: '-' }}</span>@endif
</td></tr>
@empty
<tr><td colspan="5" class="mu">Belum ada tagihan.</td></tr>
@endforelse
</table></div>
@endif
@endsection
