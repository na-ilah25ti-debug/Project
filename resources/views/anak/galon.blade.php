@extends('layouts.kos')
@section('title', 'Galon - KosKita')
@section('content')
<h1>Pesan galon</h1>
@if (! $t)
<div class="card">Akunmu belum terhubung ke kamar.</div>
@else
<p class="mu">Harga Rp {{ number_format(config('kos.harga_galon'), 0, ',', '.') }} per galon. Bayar ke ibu kos saat galon diantar.</p>
<form class="card" method="POST" action="{{ route('anak.galon.store') }}" style="max-width:480px">
@csrf
<label>Jumlah galon <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" max="10" required></label>
<label>Catatan (opsional) <input name="catatan" value="{{ old('catatan') }}" maxlength="150" placeholder="Contoh: antar sore ya"></label>
<p style="margin-top:14px"><button class="btn">Pesan galon</button></p>
</form>
<h2>Riwayat pesanan</h2>
<div class="scroll"><table>
<tr><th>Tanggal</th><th>Jumlah</th><th>Total</th><th>Catatan</th><th>Status</th></tr>
@forelse ($orders as $o)
<tr><td>{{ $o->created_at->format('d/m/Y H:i') }}</td><td>{{ $o->jumlah }}</td><td>Rp {{ number_format($o->total, 0, ',', '.') }}</td><td>{{ $o->catatan }}</td><td><span class="chip b-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td></tr>
@empty
<tr><td colspan="5" class="mu">Belum ada pesanan.</td></tr>
@endforelse
</table></div>
@endif
@endsection
