<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = ['room_id', 'tenant_id', 'judul', 'deskripsi', 'status'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
