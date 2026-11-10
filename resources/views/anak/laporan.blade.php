@extends('layouts.kos')
@section('title', 'Lapor Masalah - KosKita')
@section('content')
<h1>Lapor masalah</h1>
@if (! $t || ! $t->room_id)
<div class="card">Akunmu belum terhubung ke kamar.</div>
@else
<p class="mu">Laporan untuk {{ $t->room->label }}. Pantau statusnya di bawah.</p>
<form class="card" method="POST" action="{{ route('anak.laporan.store') }}" style="max-width:560px">
@csrf
<label>Masalah</label><input name="judul" value="{{ old('judul') }}" placeholder="Contoh: Keran kamar mandi bocor" required>
<label>Keterangan (opsional)</label><textarea name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
<p style="margin-top:14px"><button class="btn">Kirim laporan</button></p>
</form>
<h2>Laporanku</h2>
<div class="scroll"><table>
<tr><th>Tanggal</th><th>Masalah</th><th>Status</th></tr>
@forelse ($complaints as $c)
<tr><td>{{ $c->created_at->format('d/m/Y') }}</td><td><b>{{ $c->judul }}</b><div class="mu">{{ $c->deskripsi }}</div></td><td><span class="chip b-{{ $c->status }}">{{ ucfirst($c->status) }}</span></td></tr>
@empty
<tr><td colspan="3" class="mu">Belum ada laporan.</td></tr>
@endforelse
</table></div>
@endif
@endsection
