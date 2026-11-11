@extends('layouts.kos')
@section('title', 'Tagihan - KosKita')
@section('content')
<h1>Tagihan</h1>
<p class="mu">Catat pembayaran secara digital dan lihat siapa yang belum membayar.</p>
<div class="row card">
<form method="GET" class="row" style="flex:2"><label>Periode <input type="month" name="periode" value="{{ $periode }}"></label><div><button class="btn ghost">Tampilkan</button></div></form>
<form method="POST" action="{{ route('tagihan.generate') }}" class="row" style="flex:2">@csrf<input type="hidden" name="periode" value="{{ $periode }}"><div><button class="btn">Buat tagihan periode {{ $periode }}</button></div></form>
</div>
<div class="stats three-cols">
<div class="card stat"><b>Rp {{ number_format($terkumpul, 0, ',', '.') }}</b><span>Terkumpul</span></div>
<div class="card stat"><b>Rp {{ number_format($belumBayar, 0, ',', '.') }}</b><span>Belum dibayar</span></div>
<div class="card stat"><b>{{ $verifikasi }}</b><span>Menunggu verifikasi</span></div>
</div>
<div class="scroll"><table>
<tr><th>Penghuni</th><th>Kamar</th><th>Jumlah</th><th>Jatuh tempo</th><th>Status</th><th>Dibayar</th><th>Bukti</th><th></th></tr>
@forelse ($payments as $p)
<tr><td>{{ $p->tenant->nama }}</td><td>{{ $p->tenant->room?->label }}</td><td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td><td>{{ $p->jatuh_tempo->format('d/m/Y') }}</td>
<td><span class="chip {{ $p->terlambat ? 'b-telat' : 'b-'.$p->status }}">{{ $p->terlambat ? 'Terlambat' : ['lunas' => 'Lunas', 'belum' => 'Belum dibayar', 'menunggu' => 'Menunggu verifikasi'][$p->status] }}</span></td>
<td>{{ $p->tanggal_bayar?->format('d/m/Y') ?? '-' }}</td>
<td>@if ($p->bukti)<a href="{{ route('bukti.show', $p) }}" target="_blank">{{ $p->metode }}</a>@else - @endif</td>
<td style="white-space:nowrap">@if ($p->status !== 'lunas')<form class="inline" method="POST" action="{{ route('tagihan.lunas', $p) }}">@csrf @method('PATCH')<button class="btn sm">{{ $p->status === 'menunggu' ? 'Terima' : 'Tandai lunas' }}</button></form>@endif
@if ($p->status === 'menunggu')<form class="inline" method="POST" action="{{ route('tagihan.tolak', $p) }}" onsubmit="return confirm('Tolak bukti ini?')">@csrf @method('PATCH')<button class="btn red sm">Tolak</button></form>@endif</td></tr>
@empty
<tr><td colspan="8" class="mu">Belum ada tagihan untuk periode ini. Klik "Buat tagihan".</td></tr>
@endforelse
</table></div>
@endsection
