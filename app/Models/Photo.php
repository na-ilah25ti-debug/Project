<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $fillable = ['house_id', 'room_id', 'jenis', 'path', 'keterangan'];

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getUrlAttribute(): string
    {
        return route('media.foto', $this);
    }

    protected static function booted(): void
    {
        static::deleting(fn (Photo $p) => Storage::disk('public')->delete($p->path));
    }
}
