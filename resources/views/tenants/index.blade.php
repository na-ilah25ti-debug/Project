@extends('layouts.kos')
@section('title', 'Penghuni - KosKita')
@section('content')
<div class="row" style="align-items:center"><div><h1>Penghuni</h1><p class="mu" style="margin:0">Status kamar ikut berubah otomatis saat penghuni masuk atau keluar.</p></div>
<div style="text-align:right;flex:0"><a class="btn" href="{{ route('penghuni.create') }}" style="white-space:nowrap">+ Penghuni masuk</a></div></div>
<div class="scroll" style="margin-top:16px"><table>
<tr><th>Nama</th><th>No. HP</th><th>Kamar</th><th>Akun</th><th>Masuk</th><th>Keluar</th><th></th></tr>
@forelse ($tenants as $t)
<tr><td>{{ $t->nama }}</td><td>{{ $t->no_hp }}</td><td>{{ $t->room?->label }}</td><td>{{ $t->user?->email ?? '-' }}</td><td>{{ $t->tanggal_masuk->format('d/m/Y') }}</td>
<td>{{ $t->tanggal_keluar?->format('d/m/Y') ?? '-' }}</td>
<td>@if ($t->aktif)<form class="inline" method="POST" action="{{ route('penghuni.keluar', $t) }}" onsubmit="return confirm('Tandai {{ $t->nama }} keluar?')">@csrf @method('PATCH')<button class="btn ghost sm">Keluar</button></form>@else<span class="mu">Tidak aktif</span>@endif</td></tr>
@empty
<tr><td colspan="7" class="mu">Belum ada penghuni.</td></tr>
@endforelse
</table></div>
@endsection
