@props(['message' => '', 'room' => null])
<form class="contact-form" method="POST" action="{{ route('pesan.kirim') }}">
    @csrf
    @if ($room)
        <input type="hidden" name="room_id" value="{{ $room->id }}">
    @endif
    <div class="form-pair">
        <label>Nama
            <input name="nama" value="{{ old('nama', auth()->user()?->name) }}" maxlength="100" autocomplete="name" required>
        </label>
        <label>No. WhatsApp
            <input name="kontak" value="{{ old('kontak', auth()->user()?->tenant?->no_hp) }}" type="tel" maxlength="20" placeholder="08xxxxxxxxxx" autocomplete="tel" required>
        </label>
    </div>
    <label>Pesan
        <textarea name="isi" rows="4" maxlength="1000" required>{{ old('isi', $message) }}</textarea>
    </label>
    <button class="btn" type="submit">Kirim pesan <x-kos-icon name="arrow" :width="17" :height="17" /></button>
</form>
