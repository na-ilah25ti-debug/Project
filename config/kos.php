<?php

return [
    'whatsapp' => env('KOS_WHATSAPP'),
    'nama_ibu' => env('KOS_NAMA_IBU', 'pemilik kos'),
    'rekening' => env('KOS_REKENING'),
    'harga_galon' => (int) env('KOS_HARGA_GALON', 6000),
];
