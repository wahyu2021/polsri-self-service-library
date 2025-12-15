<?php

namespace App\Interfaces;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Logbook;

interface LogbookRepositoryInterface
{
    public function getVisitorsCountByDate(Carbon $date): int;
    public function getRecentEntries(int $limit = 10): Collection;
    public function create(array $data): Logbook;
    public function getAllWithFilters(array $filters, int $perPage = 15);
    public function getWeeklyStats(): array;
}
