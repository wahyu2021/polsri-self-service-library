<?php

namespace App\Repositories;

use App\Interfaces\LogbookRepositoryInterface;
use App\Models\Logbook;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class LogbookRepository implements LogbookRepositoryInterface
{
    public function getVisitorsCountByDate(Carbon $date): int
    {
        return Logbook::whereDate('created_at', $date)->count();
    }

    public function getRecentEntries(int $limit = 10): Collection
    {
        return Logbook::with('user')
                      ->latest()
                      ->limit($limit)
                      ->get();
    }

    public function create(array $data): Logbook
    {
        return Logbook::create($data);
    }
}
