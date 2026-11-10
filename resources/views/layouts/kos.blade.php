<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'KosKita')</title>
<style>
:root{--bg:#fbfaf7;--sf:#fff;--tx:#1d2a24;--mu:#5d6b64;--ln:#e3e8e4;--br:#2f7d5b;--g:#d5e8d4;--r:#f8cecc;--p:#e1d5e7;--y:#fff2cc}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--tx);font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;line-height:1.55}
.wrap{max-width:1080px;margin:0 auto;padding:0 18px}
header.top{background:var(--sf);border-bottom:1px solid var(--ln)}
header.top .wrap{display:flex;align-items:center;gap:6px;flex-wrap:wrap;padding-top:12px;padding-bottom:12px}
.logo{font-weight:800;font-size:19px;margin-right:14px;color:var(--tx);text-decoration:none}.logo span{color:var(--br)}
header.top a.nav{color:var(--mu);text-decoration:none;padding:6px 12px;border-radius:999px;font-size:14px}
header.top a.nav.on{background:var(--tx);color:#fff}
header.top form{margin-left:auto}
main{padding:28px 0 60px}
h1{font-size:26px;margin:0 0 4px}h2{font-size:18px;margin:26px 0 10px}
.mu{color:var(--mu)}
.card{background:var(--sf);border:1px solid var(--ln);border-radius:16px;padding:18px}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin:18px 0}
.stat b{display:block;font-size:26px}.stat span{color:var(--mu);font-size:13px}
table{width:100%;border-collapse:collapse;background:var(--sf);border:1px solid var(--ln);border-radius:12px;overflow:hidden;font-size:14.5px}
th,td{padding:10px 12px;text-align:left;border-bottom:1px solid var(--ln);vertical-align:top}
th{background:#f3f5f2;font-size:13px;color:var(--mu)}
.scroll{overflow-x:auto}
.btn{display:inline-block;background:var(--tx);color:#fff;border:0;border-radius:999px;padding:8px 16px;font:inherit;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none}
.btn.sm{padding:5px 12px;font-size:13px}.btn.ghost{background:transparent;color:var(--tx);border:1px solid var(--ln)}.btn.red{background:#b85450}
.chip{display:inline-block;padding:3px 11px;border-radius:999px;font-size:12px;font-weight:700}
.b-kosong,.b-lunas,.b-selesai{background:var(--g)}.b-terisi,.b-belum,.b-diterima{background:var(--r)}.b-perawatan,.b-diproses{background:var(--y)}.b-telat{background:#b85450;color:#fff}.b-menunggu{background:var(--y)}.b-dipesan{background:var(--r)}.b-diantar{background:var(--y)}.b-batal{background:#e5e5e5}.b-putri{background:#f5d0e6}.b-putra{background:#dae8fc}.house{margin-top:30px}.house h2{display:flex;align-items:center;gap:10px;margin:0 0 2px}
label{display:block;font-weight:600;font-size:14px;margin:14px 0 5px}
input,select,textarea{width:100%;padding:10px 12px;border:1px solid var(--ln);border-radius:10px;font:inherit;background:var(--sf);color:var(--tx)}
.row{display:flex;gap:12px;flex-wrap:wrap;align-items:end}.row>*{flex:1;min-width:150px}
.flash{background:var(--g);border-radius:12px;padding:10px 14px;margin:14px 0}
.err{background:var(--r);border-radius:12px;padding:10px 14px;margin:14px 0}
.rooms{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:14px;margin-top:16px}
.room h3{margin:0 0 4px;display:flex;justify-content:space-between;align-items:center}
.inline{display:inline}
.photo{width:100%;height:150px;object-fit:cover;border-radius:12px;margin-bottom:10px;background:#eee}
.hero{width:100%;max-height:300px;object-fit:cover;border-radius:18px;margin-bottom:18px}
.gallery{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;margin:12px 0}
.gallery img{width:100%;height:110px;object-fit:cover;border-radius:12px}
.wa{background:#25a244;color:#fff}
.bubble{background:#f3f5f2;border-radius:12px;padding:10px 14px;margin-top:8px}
@media(max-width:700px){.stats{grid-template-columns:repeat(2,1fr)}}
</style>
</head>
<body>
<header class="top"><div class="wrap">
<a class="logo" href="{{ route('home') }}">Kos<span>Kita</span></a>
@auth
@if (auth()->user()->role === 'pemilik')
@foreach ([['admin.dashboard','Dashboard','admin.dashboard'],['kamar.index','Kamar','kamar.*'],['foto.index','Foto','foto.*'],['penghuni.index','Penghuni','penghuni.*'],['tagihan.index','Tagihan','tagihan.*'],['galon.index','Galon','galon.*'],['laporan.index','Laporan','laporan.*'],['pesan.index','Pesan','pesan.index']] as [$r,$l,$m])
<a class="nav {{ request()->routeIs($m) ? 'on' : '' }}" href="{{ route($r) }}">{{ $l }}</a>
@endforeach
@else
@foreach ([['anak.home','Beranda'],['anak.tagihan','Tagihan'],['anak.galon','Galon'],['anak.laporan','Lapor masalah'],['anak.pesan','Pesan']] as [$r,$l])
<a class="nav {{ request()->routeIs($r) ? 'on' : '' }}" href="{{ route($r) }}">{{ $l }}</a>
@endforeach
@endif
<form method="POST" action="{{ route('logout') }}">@csrf<button class="btn ghost sm">Keluar</button></form>
@else
<a class="nav" href="{{ route('login') }}" style="margin-left:auto">Masuk</a>
@endauth
</div></header>
<main><div class="wrap">
@if (session('ok'))<div class="flash">{{ session('ok') }}</div>@endif
@if ($errors->any())<div class="err">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
@yield('content')
</div></main>
</body>
</html>
