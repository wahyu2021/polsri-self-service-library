<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use App\Services\BookService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected StudentService $studentService;
    protected BookService $bookService;

    public function __construct(StudentService $studentService, BookService $bookService)
    {
        $this->studentService = $studentService;
        $this->bookService = $bookService;
    }

    public function index()
    {
        $user = Auth::user();
        $data = $this->studentService->getStudentDashboardData($user->id);
        
        // Fetch external API data
        $recommendations = $this->bookService->getExternalBooksInspiration();

        return view('student.dashboard', [
            'user' => $user,
            'activeLoans' => $data['activeLoans'],
            'historyLoans' => $data['historyLoans'],
            'recommendations' => $recommendations
        ]);
    }
}
