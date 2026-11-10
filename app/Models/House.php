<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $fillable = ['nama', 'jenis', 'fasilitas_umum'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
