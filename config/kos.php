<?php

// Atur lewat file .env (KOS_WHATSAPP, KOS_NAMA_IBU, KOS_REKENING, KOS_HARGA_GALON)
return [
    'whatsapp' => env('KOS_WHATSAPP', '6281234567890'), // format 62..., tanpa + dan spasi
    'nama_ibu' => env('KOS_NAMA_IBU', 'Ibu Kos'),
    'rekening' => env('KOS_REKENING', 'BRI 1234-5678-90 a.n. Ibu Kos'),
    'harga_galon' => (int) env('KOS_HARGA_GALON', 6000),
];
