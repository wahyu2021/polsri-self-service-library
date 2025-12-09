<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface SettingRepositoryInterface
{
    public function getAll();
    public function updateByKey(string $key, string $value);
}
