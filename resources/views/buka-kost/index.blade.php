@extends('layouts.kos')
@section('title', 'Pengajuan Buka Kos - KosKita')
@section('content')
<h1>Pengajuan buka kos</h1>
<p class="mu">Pengajuan dari halaman "Tertarik buka kos". Hubungi pengaju lewat nomor yang dicantumkan.</p>
<div class="scroll"><table>
<tr><th>Tanggal</th><th>Pengelola</th><th>Kos</th><th>Rekening</th><th>Status</th><th>Ubah</th></tr>
@forelse ($items as $a)
@php
    $n = preg_replace('/\D/', '', $a->no_hp);
    if (str_starts_with($n, '0')) { $n = '62' . substr($n, 1); }
@endphp
<tr><td>{{ $a->created_at->format('d/m/Y') }}</td>
<td><b>{{ $a->nama_pengelola }}</b><div class="mu">{{ $a->no_hp }}@if ($a->email) &middot; {{ $a->email }}@endif</div><a target="_blank" rel="noopener" href="https://wa.me/{{ $n }}">WhatsApp</a></td>
<td><b>{{ $a->nama_kos }}</b> <span class="chip b-{{ $a->jenis === 'putra' ? 'putra' : 'putri' }}">{{ ucfirst($a->jenis) }}</span><div class="mu">{{ $a->jumlah_kamar }} kamar &middot; {{ $a->alamat }}</div></td>
<td>{{ $a->bank }}<div class="mu">{{ $a->no_rekening }} a.n. {{ $a->nama_rekening }}</div></td>
<td><span class="chip b-{{ ['baru'=>'menunggu','disetujui'=>'lunas','ditolak'=>'telat'][$a->status] }}">{{ ucfirst($a->status) }}</span></td>
<td><form method="POST" action="{{ route('pengajuan.update', $a) }}" class="row" style="flex-wrap:nowrap">@csrf @method('PATCH')
<select name="status">@foreach (['baru','disetujui','ditolak'] as $s)<option value="{{ $s }}" @selected($a->status===$s)>{{ ucfirst($s) }}</option>@endforeach</select><button class="btn sm" style="flex:0">Simpan</button></form></td></tr>
@empty
<tr><td colspan="6" class="mu">Belum ada pengajuan.</td></tr>
@endforelse
</table></div>
@endsection
