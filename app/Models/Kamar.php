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
        'promo_aktif',
        'harga_promo',
        'promo_mulai',
        'promo_selesai',
        'label_promo',
        'deskripsi',
        'fasilitas',
        'status',
        'meta',
        'is_featured',
        'show_to_public',
        'images',
        'foto_360',
        'warna_dinding',
        'warna_lantai',
        'warna_kasur',
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
        'promo_aktif'    => 'boolean',
        'promo_mulai'    => 'date',
        'promo_selesai'  => 'date',
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

    public function activePenyewaan(): HasOne
    {
        return $this->hasOne(Penyewaan::class)->whereIn('status', ['approved', 'active']);
    }

    public function getTanggalTersediaKembaliAttribute(): ?\Carbon\Carbon
    {
        $activeSewa = $this->penyewaan()
            ->whereIn('status', ['approved', 'active'])
            ->orderByDesc('tanggal_keluar')
            ->orderByDesc('tanggal_masuk')
            ->first();

        if ($activeSewa) {
            if ($activeSewa->tanggal_keluar) {
                return \Carbon\Carbon::parse($activeSewa->tanggal_keluar)->addDay();
            }
            return \Carbon\Carbon::parse($activeSewa->tanggal_masuk)->addMonths($activeSewa->durasi_bulan)->addDay();
        }

        return null;
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
        return $query->byHargaEfektif($min, $max);
    }

    public function scopeByHargaEfektif($query, $min, $max = null)
    {
        $today = now()->toDateString();

        return $query->where(function ($q) use ($min, $max, $today) {
            $q->where(function ($promo) use ($min, $max, $today) {
                $promo->where('promo_aktif', true)
                    ->whereNotNull('harga_promo')
                    ->whereColumn('harga_promo', '<', 'harga_bulanan')
                    ->where(function ($dates) use ($today) {
                        $dates->whereNull('promo_mulai')->orWhere('promo_mulai', '<=', $today);
                    })
                    ->where(function ($dates) use ($today) {
                        $dates->whereNull('promo_selesai')->orWhere('promo_selesai', '>=', $today);
                    })
                    ->where('harga_promo', '>=', $min);

                if ($max) {
                    $promo->where('harga_promo', '<=', $max);
                }
            })->orWhere(function ($normal) use ($min, $max, $today) {
                $normal->where(function ($noPromo) use ($today) {
                    $noPromo->where('promo_aktif', false)
                        ->orWhereNull('harga_promo')
                        ->orWhereColumn('harga_promo', '>=', 'harga_bulanan')
                        ->orWhere(function ($exp) use ($today) {
                            $exp->whereNotNull('promo_selesai')->where('promo_selesai', '<', $today);
                        })
                        ->orWhere(function ($exp) use ($today) {
                            $exp->whereNotNull('promo_mulai')->where('promo_mulai', '>', $today);
                        });
                })->where('harga_bulanan', '>=', $min);

                if ($max) {
                    $normal->where('harga_bulanan', '<=', $max);
                }
            });
        });
    }

    public function isPromoAktif(): bool
    {
        if (!$this->promo_aktif || !$this->harga_promo || $this->harga_promo >= $this->harga_bulanan) {
            return false;
        }

        $today = now()->startOfDay();

        if ($this->promo_mulai && $today->lt($this->promo_mulai)) {
            return false;
        }

        if ($this->promo_selesai && $today->gt($this->promo_selesai)) {
            return false;
        }

        return true;
    }

    public function getHargaEfektifAttribute(): int
    {
        return $this->isPromoAktif() ? (int) $this->harga_promo : (int) $this->harga_bulanan;
    }

    public function getPotonganPromoAttribute(): int
    {
        return $this->isPromoAktif()
            ? (int) ($this->harga_bulanan - $this->harga_promo)
            : 0;
    }

    public function getPersenDiskonPromoAttribute(): ?int
    {
        if (!$this->isPromoAktif() || $this->harga_bulanan <= 0) {
            return null;
        }

        return (int) round(($this->potongan_promo / $this->harga_bulanan) * 100);
    }
}
