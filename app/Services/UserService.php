<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateAvatar(User $user, UploadedFile $file)
    {
        // Delete old avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $file->store('avatars', 'public');

        return $this->userRepository->update($user, ['avatar' => $path]);
    }

    public function getAllUsers(array $filters)
    {
        return $this->userRepository->getAllPaginated($filters);
    }

    public function getStudents()
    {
        return $this->userRepository->getStudents();
    }

    public function createUser(array $data)
    {
        // Password hashing handled in FormRequest via passedValidation usually, 
        // but if data comes from elsewhere, service handles business logic.
        // We assume clean data here.
        return $this->userRepository->create($data);
    }

    public function updateUser(User $user, array $data)
    {
        return $this->userRepository->update($user, $data);
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            throw new \Exception('Anda tidak bisa menghapus akun sendiri.');
        }
        
        return $this->userRepository->delete($user);
    }
}
