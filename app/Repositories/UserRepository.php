<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = User::query();

        if (isset($filters['role']) && $filters['role']) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getStudents(): Collection
    {
        return User::where('role', UserRole::MAHASISWA)->orderBy('name')->get();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findStudentByNim(string $nim): ?User
    {
        return User::where('nim', $nim)->where('role', UserRole::MAHASISWA)->first();
    }

    public function searchStudents(string $query, int $limit = 5): Collection
    {
        return User::where('role', UserRole::MAHASISWA)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('nim', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get();
    }

    public function searchUsers(string $query, int $limit = 5): Collection
    {
        return User::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('nim', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get();
    }
}
