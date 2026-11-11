@extends('layouts.kos')
@section('title', 'Pesan masuk · KosKita')
@section('content')
<h1>Pesan masuk</h1>
<p class="mu">Balas pengunjung lewat WhatsApp dan penghuni lewat dasbor.</p>
@forelse ($messages as $message)
    @php
        $number = preg_replace('/\D/', '', $message->kontak ?? '');
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        $waLink = preg_match('/^628[0-9]{7,12}$/', $number)
            ? 'https://wa.me/' . $number . '?text=' . rawurlencode('Halo ' . $message->nama . ', saya menanggapi pesanmu di KosKita.')
            : null;
    @endphp
    <article class="card message-card">
        <div class="split">
            <div><strong>{{ $message->nama }}</strong> <small>· {{ $message->created_at->format('d/m/Y H:i') }}</small></div>
            @unless ($message->dibaca)<span class="chip b-dipesan">Baru</span>@endunless
        </div>
        <p class="mu">{{ $message->user_id ? 'Akun penghuni' : 'Pengunjung' }} @if ($message->kontak) · {{ $message->kontak }} @endif</p>
        <p class="message-body">{{ $message->isi }}</p>
        @if ($message->balasan)
            <div class="bubble"><strong>Balasan</strong> <small>· {{ $message->balasan_at?->format('d/m/Y H:i') }}</small><p>{{ $message->balasan }}</p></div>
        @endif
        @if ($message->user_id)
            <form method="POST" action="{{ route('pesan.balas', $message) }}" style="margin-top:14px">
                @csrf
                @method('PATCH')
                <label>Balasan untuk penghuni <textarea name="balasan" rows="2" maxlength="1000" required>{{ $message->balasan }}</textarea></label>
                <button type="submit" class="btn sm">Simpan balasan</button>
            </form>
        @endif
        <div class="actions">
            @if ($waLink)<a class="btn ghost sm" href="{{ $waLink }}" target="_blank" rel="noopener">Balas via WhatsApp <x-kos-icon name="external" :width="15" :height="15" /></a>@endif
            @unless ($message->dibaca)
                <form method="POST" action="{{ route('pesan.baca', $message) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn ghost sm">Tandai dibaca</button>
                </form>
            @endunless
            <form method="POST" action="{{ route('pesan.destroy', $message) }}" onsubmit="return confirm('Hapus pesan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn ghost sm">Hapus</button>
            </form>
        </div>
    </article>
@empty
    <div class="card mu">Belum ada pesan.</div>
@endforelse
@endsection
