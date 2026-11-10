<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalonOrder extends Model
{
    protected $fillable = ['tenant_id', 'jumlah', 'harga_satuan', 'catatan', 'status'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getTotalAttribute(): int
    {
        return $this->jumlah * $this->harga_satuan;
    }
}
