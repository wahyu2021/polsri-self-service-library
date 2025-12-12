<?php

namespace App\Services;

use App\Interfaces\LogbookRepositoryInterface;

class LogbookService
{
    protected LogbookRepositoryInterface $logbookRepository;

    public function __construct(LogbookRepositoryInterface $logbookRepository)
    {
        $this->logbookRepository = $logbookRepository;
    }

    public function getLogbooks(array $filters)
    {
        return $this->logbookRepository->getAllWithFilters($filters);
    }
}
