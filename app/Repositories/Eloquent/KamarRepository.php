<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\KamarRepositoryInterface;
use App\Enums\StatusKamar;
use App\Models\Kamar;
use Illuminate\Pagination\LengthAwarePaginator;

class KamarRepository extends BaseRepository implements KamarRepositoryInterface
{
    public function __construct(Kamar $model)
    {
        parent::__construct($model);
    }

    public function findAvailable(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        // Tampilkan tersedia, maintenance, dan reserved — hanya sembunyikan yang benar-benar terisi
        // Tambahan: hanya kamar yang diizinkan admin untuk tampil ke publik (show_to_public = true)
        $visibleStatuses = [
            StatusKamar::Tersedia->value,
            StatusKamar::Terisi->value,
            StatusKamar::Maintenance->value,
            StatusKamar::Reserved->value,
        ];

        $query = $this->model
            ->whereIn('status', $visibleStatuses)
            ->where('show_to_public', true)
            ->with(['thumbnail', 'fasilitasMaster'])
            ->withCount(['penyewaan as penghuni_aktif_count' => function ($query) {
                $query->whereIn('status', ['approved', 'active']);
            }]);

        // Filter by gender (putra/putri/campur)
        if (!empty($filters['gender'])) {
            $query->byGender($filters['gender']);
        }

        // Filter by tipe (standar/deluxe/vip/suite)
        if (!empty($filters['tipe'])) {
            $query->byTipe($filters['tipe']);
        }

        if (!empty($filters['min_harga']) || !empty($filters['max_harga'])) {
            $min = $filters['min_harga'] ?? 0;
            $max = $filters['max_harga'] ?? null;
            $query->byHarga($min, $max);
        }

        // Filter by fasilitas
        if (!empty($filters['fasilitas'])) {
            $query->whereHas('fasilitasMaster', function ($q) use ($filters) {
                $q->whereIn('fasilitas.id', (array) $filters['fasilitas']);
            });
        }

        return $query->paginate($perPage);
    }

    public function findWithGallery($id)
    {
        return $this->model->with(['fotoKamar', 'fasilitasMaster'])
            ->where('show_to_public', true)
            ->withCount(['penyewaan as penghuni_aktif_count' => function ($query) {
                $query->whereIn('status', ['approved', 'active']);
            }])
            ->findOrFail($id);
    }
}
