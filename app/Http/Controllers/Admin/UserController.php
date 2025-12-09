<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->getAllUsers($request->all());
        return view('admin.management-user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.management-user.create');
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.management-user.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}