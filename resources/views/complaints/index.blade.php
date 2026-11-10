@extends('layouts.kos')
@section('title', 'Laporan - KosKita')
@section('content')
<h1>Laporan kerusakan</h1>
<p class="mu">Catat laporan penghuni dan pantau status penanganannya.</p>
<form class="card" method="POST" action="{{ route('laporan.store') }}">
@csrf
<div class="row">
<div><label>Kamar</label><select name="room_id" required>@foreach ($rooms as $r)<option value="{{ $r->id }}">{{ $r->label }}</option>@endforeach</select></div>
<div style="flex:2"><label>Masalah</label><input name="judul" placeholder="Contoh: AC mati" required></div>
</div>
<label>Keterangan (opsional)</label><textarea name="deskripsi" rows="2"></textarea>
<p style="margin-top:14px"><button class="btn">Catat laporan</button></p>
</form>
<h2>Daftar laporan</h2>
<div class="scroll"><table>
<tr><th>Tanggal</th><th>Kamar</th><th>Masalah</th><th>Status</th><th>Ubah status</th></tr>
@forelse ($complaints as $c)
<tr><td>{{ $c->created_at->format('d/m/Y') }}</td><td>{{ $c->room->label }}</td><td><b>{{ $c->judul }}</b><div class="mu">{{ $c->deskripsi }}</div></td>
<td><span class="chip b-{{ $c->status }}">{{ ucfirst($c->status) }}</span></td>
<td><form method="POST" action="{{ route('laporan.update', $c) }}" class="row" style="flex-wrap:nowrap">@csrf @method('PATCH')
<select name="status">@foreach (['diterima','diproses','selesai'] as $s)<option value="{{ $s }}" @selected($c->status===$s)>{{ ucfirst($s) }}</option>@endforeach</select><button class="btn sm" style="flex:0">Simpan</button></form></td></tr>
@empty
<tr><td colspan="5" class="mu">Belum ada laporan.</td></tr>
@endforelse
</table></div>
@endsection
