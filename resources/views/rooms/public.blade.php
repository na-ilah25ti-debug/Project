@extends('layouts.publik')
@section('title', 'KosKita · Kos putri & putra')
@section('content')
@php
    $jumlahKosong = $houses->sum('kosong_kamar');
    $statusKamar = ['kosong' => 'Tersedia', 'terisi' => 'Terisi', 'perawatan' => 'Perawatan'];
    $whatsapp = config('kos.whatsapp');
    $waLink = $whatsapp ? 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode('Halo, saya ingin bertanya tentang kamar kos yang tersedia.') : null;
@endphp
<div class="wrap">
    <section class="landing-hero" aria-labelledby="judul-beranda">
        <div class="hero-intro">
            <div>
                <p class="eyebrow"><span></span> Kos putri &amp; putra</p>
                <h1 id="judul-beranda">Temukan kos yang<br class="desktop-break"> terasa <em>pas.</em></h1>
            </div>
            <div class="hero-actions">
                <p>Cek kamar kosong, bandingkan fasilitas, dan atur jadwal survei langsung dengan pemilik kos.</p>
                <div class="actions">
                    <a class="btn" href="#kamar">Jelajahi kamar <x-kos-icon name="arrow" :width="17" :height="17" /></a>
                    <a class="text-link" href="{{ $waLink ?: '#pesan' }}" @if($waLink) target="_blank" rel="noopener" @endif>{{ $waLink ? 'WhatsApp' : 'Kirim pesan' }} <x-kos-icon name="external" :width="16" :height="16" /></a>
                </div>
            </div>
        </div>
        <figure class="hero-photo">
            <img src="{{ $banner?->url ?? asset('koskita/hero.webp') }}" alt="{{ $banner?->keterangan ?: ($banner ? 'Foto rumah kos' : 'Ilustrasi halaman rumah kos') }}" fetchpriority="high">
            @unless ($banner)<figcaption>Ilustrasi suasana kos</figcaption>@endunless
        </figure>
        <div class="inventory-strip">
            <span><x-kos-icon name="home" /> <strong>{{ $houses->count() }}</strong> rumah kos</span>
            <span><x-kos-icon name="bed" /> <strong>{{ $houses->sum('total_kamar') }}</strong> total kamar</span>
            <a href="{{ route('home', ['status' => 'kosong']) }}#kamar"><span class="status-dot"></span><span><strong>{{ $jumlahKosong }}</strong> kamar tersedia</span><x-kos-icon name="arrow" :width="16" :height="16" /></a>
        </div>
    </section>

    <section id="rumah" class="section" aria-labelledby="judul-rumah">
        <div class="section-heading"><h2 id="judul-rumah">Rumah kos kami</h2></div>
        <div class="house-grid">
            @forelse ($houses as $house)
                @php $foto = $house->photos->firstWhere('jenis', 'rumah'); @endphp
                <a class="house-tile" href="{{ route('home', ['rumah' => $house->id]) }}#kamar">
                    @if ($foto)<img src="{{ $foto->url }}" alt="{{ $house->nama }}" loading="lazy">@endif
                    <div class="house-tile-body">
                        <div class="split"><span class="pill">{{ $house->jenis === 'putri' ? 'Kos putri' : 'Kos putra' }}</span><span class="circle-arrow"><x-kos-icon name="external" :width="18" :height="18" /></span></div>
                        <h3>{{ $house->nama }}</h3>
                        <p>{{ $house->total_kamar }} kamar <span aria-hidden="true">·</span> {{ $house->kosong_kamar }} tersedia</p>
                    </div>
                </a>
            @empty
                <p class="mu">Data rumah kos sedang disiapkan.</p>
            @endforelse
        </div>
    </section>

    <section id="kamar" class="section catalog" aria-labelledby="judul-kamar">
        <div class="section-heading">
            <div><h2 id="judul-kamar">Cari kamar</h2><p class="mu">{{ $rooms->total() }} kamar ditemukan</p></div>
            @if ($filterAktif)<a class="text-link" href="{{ route('home') }}#kamar">Reset filter <x-kos-icon name="close" :width="16" :height="16" /></a>@endif
        </div>
        <form method="GET" action="{{ route('home') }}#kamar" class="filter-form">
            <label>Rumah kos
                <select name="rumah">
                    <option value="">Semua rumah</option>
                    @foreach ($houses as $house)
                        <option value="{{ $house->id }}" @selected(request('rumah') == $house->id)>{{ $house->nama }}</option>
                    @endforeach
                </select>
            </label>
            <label>Penghuni
                <select name="jenis">
                    <option value="">Putri &amp; putra</option>
                    <option value="putri" @selected(request('jenis') === 'putri')>Putri</option>
                    <option value="putra" @selected(request('jenis') === 'putra')>Putra</option>
                </select>
            </label>
            <label>Harga maksimal
                <select name="maks">
                    <option value="">Semua harga</option>
                    @foreach ([500000, 750000, 1000000] as $harga)
                        <option value="{{ $harga }}" @selected(request('maks') == $harga)>Rp {{ number_format($harga, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </label>
            <label>Status
                <select name="status">
                    <option value="">Semua status</option>
                    @foreach ($statusKamar as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label>Tipe
                <select name="tipe">
                    <option value="">Semua tipe</option>
                    @foreach ($tipe as $type)
                        <option @selected(request('tipe') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </label>
            <button class="btn" type="submit"><x-kos-icon name="search" :width="17" :height="17" /> Cari</button>
        </form>
        <div class="room-grid">
            @forelse ($rooms as $room)
                @php
                    $foto = $room->photos->first();
                    $fasilitas = collect(explode(',', (string) $room->fasilitas))->map(fn ($item) => trim($item))->filter();
                @endphp
                <a class="room-tile" href="{{ route('kamar.detail', $room) }}">
                    @if ($foto)<img src="{{ $foto->url }}" alt="{{ $room->label }}" loading="lazy">@endif
                    <div class="room-tile-body">
                        <div class="split"><span class="eyebrow">{{ $room->house->nama }}</span><span class="chip b-{{ $room->status }}">{{ $statusKamar[$room->status] }}</span></div>
                        <h3>Kamar {{ $room->nomor }} <span class="mu">· {{ $room->tipe }}</span></h3>
                        @if($fasilitas->isNotEmpty())<div class="facilities">@foreach($fasilitas->take(3) as $item)<span>{{ $item }}</span>@endforeach</div>@endif
                        <div class="room-price split">
                            <span><strong>Rp {{ number_format($room->harga, 0, ',', '.') }}</strong> <small>/ bulan</small></span>
                            <span class="circle-arrow"><x-kos-icon name="external" :width="17" :height="17" /></span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="card empty-state">Tidak ada kamar yang cocok dengan filter ini. <a href="{{ route('home') }}#kamar">Lihat semua kamar</a>.</div>
            @endforelse
        </div>
        @if ($rooms->hasPages())
            <nav class="pagination" aria-label="Halaman daftar kamar">
                @if ($rooms->previousPageUrl())<a class="btn ghost" href="{{ $rooms->previousPageUrl() }}">Sebelumnya</a>@endif
                <span>Halaman {{ $rooms->currentPage() }} dari {{ $rooms->lastPage() }}</span>
                @if ($rooms->nextPageUrl())<a class="btn ghost" href="{{ $rooms->nextPageUrl() }}">Berikutnya <x-kos-icon name="arrow" :width="16" :height="16" /></a>@endif
            </nav>
        @endif
    </section>

    <section id="pesan" class="section contact-section" aria-labelledby="judul-kontak">
        <div>
            <h2 id="judul-kontak">Mau survei kamar?</h2>
            <p>Tanyakan alamat, foto kamar, atau jadwal survei ke {{ config('kos.nama_ibu') }}. Balasan dikirim ke nomor WhatsApp yang kamu isi.</p>
            @if ($waLink)<a class="btn ghost" href="{{ $waLink }}" target="_blank" rel="noopener"><x-kos-icon name="chat" /> Chat WhatsApp <x-kos-icon name="external" :width="15" :height="15" /></a>@endif
        </div>
        <x-kos-contact />
    </section>
    <section class="section faq" aria-labelledby="judul-faq">
        <h2 id="judul-faq">Sebelum menyewa</h2>
        <div>
            <details><summary>Bagaimana cara pesan kamar?</summary><p>Pilih kamar yang tersedia, lalu hubungi pemilik untuk survei dan menyepakati biaya. Jika cocok, pemilik akan memberi petunjuk untuk DP dan pelunasan.</p></details>
            <details><summary>Bagaimana cara mengaktifkan akun penghuni?</summary><p>Daftar dengan email yang akan kamu pakai, lalu minta pemilik kos menghubungkan akunmu ke kamar. Setelah aktif, kamu bisa melihat tagihan, melapor, dan memesan galon.</p></details>
        </div>
    </section>
</div>
@endsection
