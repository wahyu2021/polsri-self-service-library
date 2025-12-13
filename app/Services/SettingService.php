<?php

namespace App\Services;

use App\Interfaces\SettingRepositoryInterface;

class SettingService
{
    protected SettingRepositoryInterface $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function getAllSettings()
    {
        return $this->settingRepository->getAll()->keyBy('key');
    }

    public function updateSettings(array $data)
    {
        foreach ($data as $key => $value) {
            $this->settingRepository->updateByKey($key, $value);
        }

        // Clear cache so ScanService picks up new values immediately
        \Illuminate\Support\Facades\Cache::forget('setting_library_lat');
        \Illuminate\Support\Facades\Cache::forget('setting_library_lng');
        \Illuminate\Support\Facades\Cache::forget('setting_validation_radius');
    }
}
