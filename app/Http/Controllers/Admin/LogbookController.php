<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LogbookService;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    protected LogbookService $logbookService;

    public function __construct(LogbookService $logbookService)
    {
        $this->logbookService = $logbookService;
    }

    public function index(Request $request)
    {
        $logbooks = $this->logbookService->getLogbooks($request->all());

        return view('admin.logbook.index', compact('logbooks'));
    }
}
