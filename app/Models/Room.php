<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['house_id', 'nomor', 'tipe', 'harga', 'fasilitas', 'status'];

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function activeTenant()
    {
        return $this->hasOne(Tenant::class)->where('aktif', true);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class)->where('jenis', 'kamar');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // Contoh: "Saka Kost - Kamar 01"
    public function getLabelAttribute(): string
    {
        return ($this->house?->nama ?? '-') . ' - Kamar ' . $this->nomor;
    }
}
