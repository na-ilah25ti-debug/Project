<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['tenant_id', 'periode', 'jumlah', 'jatuh_tempo', 'tanggal_bayar', 'metode', 'bukti', 'status'];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getTerlambatAttribute(): bool
    {
        return $this->status === 'belum' && $this->jatuh_tempo->lt(today());
    }
}
