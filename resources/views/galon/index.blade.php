@extends('layouts.kos')
@section('title', 'Galon - KosKita')
@section('content')
<h1>Pesanan galon</h1>
<p class="mu">Pesanan dari anak kos. Harga per galon: Rp {{ number_format(config('kos.harga_galon'), 0, ',', '.') }} (atur lewat KOS_HARGA_GALON di .env).</p>
<div class="scroll"><table>
<tr><th>Tanggal</th><th>Penghuni</th><th>Kamar</th><th>Jumlah</th><th>Total</th><th>Catatan</th><th>Status</th><th>Ubah</th></tr>
@forelse ($orders as $o)
<tr><td>{{ $o->created_at->format('d/m/Y H:i') }}</td><td>{{ $o->tenant->nama }}</td><td>{{ $o->tenant->room?->label }}</td><td>{{ $o->jumlah }}</td>
<td>Rp {{ number_format($o->total, 0, ',', '.') }}</td><td>{{ $o->catatan }}</td>
<td><span class="chip b-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
<td><form method="POST" action="{{ route('galon.update', $o) }}" class="row" style="flex-wrap:nowrap">@csrf @method('PATCH')
<select name="status">@foreach (['dipesan','diantar','selesai','batal'] as $s)<option value="{{ $s }}" @selected($o->status===$s)>{{ ucfirst($s) }}</option>@endforeach</select><button class="btn sm" style="flex:0">Simpan</button></form></td></tr>
@empty
<tr><td colspan="8" class="mu">Belum ada pesanan galon.</td></tr>
@endforelse
</table></div>
@endsection
