<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id', 'nama', 'kontak', 'isi', 'balasan', 'balasan_at', 'dibaca'];

    protected $casts = ['balasan_at' => 'datetime', 'dibaca' => 'boolean'];
}
