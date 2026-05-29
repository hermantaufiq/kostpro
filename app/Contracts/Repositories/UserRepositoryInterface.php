<?php

namespace App\Contracts\Repositories;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail($email);
}
