<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KosApplication extends Model
{
    protected $fillable = [
        'nama_pengelola', 'no_hp', 'email', 'nama_kos', 'alamat', 'jenis',
        'jumlah_kamar', 'bank', 'no_rekening', 'nama_rekening', 'status',
    ];
}
