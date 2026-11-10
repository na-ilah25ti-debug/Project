@extends('layouts.kos')
@section('title', 'Kamar - KosKita')
@section('content')
<div class="row" style="align-items:center"><div><h1>Kamar</h1><p class="mu" style="margin:0">Kelola daftar kamar, harga, dan status di semua rumah kos.</p></div>
<div style="text-align:right;flex:0"><a class="btn" href="{{ route('kamar.create') }}" style="white-space:nowrap">+ Tambah kamar</a></div></div>
<div class="scroll" style="margin-top:16px"><table>
<tr><th>Rumah kos</th><th>No</th><th>Tipe</th><th>Harga</th><th>Fasilitas</th><th>Status</th><th>Penghuni</th><th></th></tr>
@foreach ($rooms as $r)
<tr><td>{{ $r->house->nama }}</td><td><b>{{ $r->nomor }}</b></td><td>{{ $r->tipe }}</td><td>Rp {{ number_format($r->harga, 0, ',', '.') }}</td><td>{{ $r->fasilitas }}</td>
<td><span class="chip b-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td><td>{{ $r->activeTenant?->nama ?? '-' }}</td>
<td style="white-space:nowrap"><a class="btn ghost sm" href="{{ route('kamar.edit', $r) }}">Ubah</a>
<form class="inline" method="POST" action="{{ route('kamar.destroy', $r) }}" onsubmit="return confirm('Hapus {{ $r->label }}?')">@csrf @method('DELETE')<button class="btn red sm">Hapus</button></form></td></tr>
@endforeach
</table></div>
@endsection
