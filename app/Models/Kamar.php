<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\StatusKamar;
use App\Enums\TipeKamar;
use App\Enums\GenderKamar;

class Kamar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kamar';

    protected $fillable = [
        'kode_kamar',
        'nama',
        'tipe',
        'gender',
        'kapasitas',
        'lantai',
        'luas',
        'harga_bulanan',
        'harga_deposit',
        'deskripsi',
        'fasilitas',
        'status',
        'meta',
        'is_featured',
        'show_to_public',
        'images',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tipe'           => TipeKamar::class,
        'gender'         => GenderKamar::class,
        'kapasitas'      => 'integer',
        'status'         => StatusKamar::class,
        'fasilitas'      => 'array',
        'meta'           => 'array',
        'is_featured'    => 'boolean',
        'show_to_public' => 'boolean',
        'images'         => 'array',
    ];

    public function fotoKamar(): HasMany
    {
        return $this->hasMany(FotoKamar::class)->orderBy('sort_order');
    }

    public function thumbnail(): HasOne
    {
        return $this->hasOne(FotoKamar::class)->where('is_thumbnail', true);
    }

    public function fasilitasMaster(): BelongsToMany
    {
        return $this->belongsToMany(Fasilitas::class, 'kamar_fasilitas');
    }

    public function inventaris(): HasMany
    {
        return $this->hasMany(Inventaris::class);
    }

    public function penyewaan(): HasMany
    {
        return $this->hasMany(Penyewaan::class);
    }

    public function getPenghuniAktifCountAttribute(): int
    {
        if (array_key_exists('penghuni_aktif_count', $this->attributes)) {
            return (int) $this->attributes['penghuni_aktif_count'];
        }

        return $this->penyewaan()
            ->whereIn('status', ['approved', 'active'])
            ->count();
    }

    public function getSisaSlotAttribute(): int
    {
        $sisa = $this->kapasitas - $this->penghuni_aktif_count;
        return max(0, $sisa);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', StatusKamar::Tersedia);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    public function scopeByHarga($query, $min, $max = null)
    {
        $query->where('harga_bulanan', '>=', $min);
        if ($max) {
            $query->where('harga_bulanan', '<=', $max);
        }
        return $query;
    }
}
