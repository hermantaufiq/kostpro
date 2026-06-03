<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\KategoriPengeluaran;

class Pengeluaran extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kategori',
        'jumlah',
        'tanggal',
        'keterangan',
        'bukti_url',
        'created_by',
    ];

    protected $casts = [
        'kategori' => KategoriPengeluaran::class,
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
