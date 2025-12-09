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
    }
}
