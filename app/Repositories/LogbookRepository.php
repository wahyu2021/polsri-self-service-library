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
        return Logbook::whereBetween('created_at', [
            $date->copy()->startOfDay(),
            $date->copy()->endOfDay()
        ])->count();
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

    public function getAllWithFilters(array $filters, int $perPage = 15)
    {
        $query = Logbook::with('user')->latest('check_in_time');

        if (!empty($filters['date'])) {
            $query->whereDate('check_in_time', $filters['date']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }
}
