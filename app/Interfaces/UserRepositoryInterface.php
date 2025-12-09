<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function getStudents(): Collection;
    public function create(array $data): User;
    public function update(User $user, array $data): User;
    public function delete(User $user): bool;
    public function findById(int $id): ?User;
}
