<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->get($columns);
    }

    public function findById($id, array $columns = ['*'], array $relations = [], array $appends = [])
    {
        return $this->model->with($relations)->findOrFail($id, $columns)->append($appends);
    }

    public function create(array $payload)
    {
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    public function update($id, array $payload)
    {
        $model = $this->findById($id);
        $model->update($payload);
        return $model->fresh();
    }

    public function deleteById($id)
    {
        $model = $this->findById($id);
        return $model->delete();
    }

    public function firstByCriteria(array $criteria, array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->where($criteria)->first($columns);
    }
}
