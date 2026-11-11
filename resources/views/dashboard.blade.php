@extends('layouts.kos')
@section('title', 'Dashboard - KosKita')
@section('content')
<h1>Ringkasan kos</h1>
<p class="mu">Pantau ketersediaan kamar, pembayaran, dan aktivitas penghuni.</p>
<div class="stats">
<div class="card stat"><b>{{ $total }}</b><span>Total kamar</span></div>
<div class="card stat"><b>{{ $kosong }}</b><span>Kosong</span></div>
<div class="card stat"><b>{{ $terisi }}</b><span>Terisi</span></div>
<div class="card stat"><b>{{ $perawatan }}</b><span>Perawatan</span></div>
</div>
<div class="stats two-cols">
<div class="card stat"><b>Rp {{ number_format($pemasukan, 0, ',', '.') }}</b><span>Pemasukan bulan ini (lunas)</span></div>
<div class="card stat"><b>Rp {{ number_format($tunggakan, 0, ',', '.') }}</b><span>Total tagihan belum dibayar</span></div>
</div>

<div class="stats three-cols">
<a class="card stat" style="text-decoration:none;color:inherit" href="{{ route('tagihan.index') }}"><b>{{ $menunggu }}</b><span>Bukti bayar menunggu verifikasi</span></a>
<a class="card stat" style="text-decoration:none;color:inherit" href="{{ route('galon.index') }}"><b>{{ $galonBaru }}</b><span>Pesanan galon baru</span></a>
<a class="card stat" style="text-decoration:none;color:inherit" href="{{ route('pesan.index') }}"><b>{{ $pesanBaru }}</b><span>Pesan belum dibaca</span></a>
</div>

<h2>Tagihan melewati jatuh tempo</h2>
<div class="scroll"><table>
<tr><th>Penghuni</th><th>Kamar</th><th>Periode</th><th>Jumlah</th><th>Jatuh tempo</th><th></th></tr>
@forelse ($terlambat as $p)
<tr><td>{{ $p->tenant->nama }}</td><td>{{ $p->tenant->room?->label }}</td><td>{{ $p->periode }}</td>
<td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td><td>{{ $p->jatuh_tempo->format('d/m/Y') }}</td>
<td><form class="inline" method="POST" action="{{ route('tagihan.lunas', $p) }}">@csrf @method('PATCH')<button class="btn sm">Tandai lunas</button></form></td></tr>
@empty
<tr><td colspan="6" class="mu">Tidak ada tagihan terlambat.</td></tr>
@endforelse
</table></div>

<h2>Laporan kerusakan yang masih terbuka</h2>
<div class="scroll"><table>
<tr><th>Kamar</th><th>Masalah</th><th>Status</th></tr>
@forelse ($laporan as $c)
<tr><td>{{ $c->room->label }}</td><td>{{ $c->judul }}</td><td><span class="chip b-{{ $c->status }}">{{ ucfirst($c->status) }}</span></td></tr>
@empty
<tr><td colspan="3" class="mu">Tidak ada laporan terbuka.</td></tr>
@endforelse
</table></div>
@endsection
