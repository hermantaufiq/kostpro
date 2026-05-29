<?php

namespace App\Contracts\Repositories;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []);
    public function findById($id, array $columns = ['*'], array $relations = [], array $appends = []);
    public function create(array $payload);
    public function update($id, array $payload);
    public function deleteById($id);
    public function firstByCriteria(array $criteria, array $columns = ['*'], array $relations = []);
}
