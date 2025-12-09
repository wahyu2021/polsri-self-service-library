<?php

namespace App\Repositories;

use App\Interfaces\SettingRepositoryInterface;
use App\Models\Setting;

class SettingRepository implements SettingRepositoryInterface
{
    public function getAll()
    {
        return Setting::all();
    }

    public function updateByKey(string $key, string $value)
    {
        return Setting::where('key', $key)->update(['value' => $value]);
    }
}
