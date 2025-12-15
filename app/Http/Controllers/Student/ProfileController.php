<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateProfileRequest;
use App\Services\UserService;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected UserService $userService;
    protected StudentService $studentService;

    public function __construct(UserService $userService, StudentService $studentService)
    {
        $this->userService = $userService;
        $this->studentService = $studentService;
    }

    public function index()
    {
        $user = Auth::user();
        $fineData = $this->studentService->getStudentFinesSummary($user->id);

        return view('student.profile', [
            'user' => $user,
            'fineData' => $fineData
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $this->userService->updateAvatar(Auth::user(), $request->file('avatar'));
            
            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
}

