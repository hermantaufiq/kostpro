<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enums\KondisiInventaris;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaris extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'kamar_id',
        'kondisi',
        'harga_beli',
        'tanggal_beli',
        'keterangan',
        'foto_url',
    ];

    protected $casts = [
        'kondisi' => KondisiInventaris::class,
        'harga_beli' => 'decimal:2',
        'tanggal_beli' => 'date',
    ];

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }
}
