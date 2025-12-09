<?php

namespace App\Services;

use App\Interfaces\LogbookRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;

class ScanService
{
    protected LogbookRepositoryInterface $logbookRepository;

    // Koordinat Perpustakaan (Contoh: Gedung A Polsri)
    const LIBRARY_LAT = -2.9850;
    const LIBRARY_LNG = 104.7320;
    const MAX_DISTANCE_METERS = 100; // Radius 100 meter

    public function __construct(LogbookRepositoryInterface $logbookRepository)
    {
        $this->logbookRepository = $logbookRepository;
    }

    public function processCheckIn(User $user, string $qrCode, float $lat, float $lng)
    {
        // 1. Validasi QR Code
        if ($qrCode !== 'LIB-POLSRI-ACCESS') {
            throw new \Exception('QR Code tidak valid.');
        }

        // 2. Validasi Jarak (Geofencing)
        $distance = $this->calculateDistance($lat, $lng, self::LIBRARY_LAT, self::LIBRARY_LNG);
        
        if ($distance > self::MAX_DISTANCE_METERS) {
            throw new \Exception("Anda berada di luar jangkauan perpustakaan. Jarak: " . round($distance) . " meter.");
        }

        // 3. Simpan Logbook
        return $this->logbookRepository->create([
            'user_id' => $user->id,
            'check_in_time' => Carbon::now(),
            'latitude' => $lat,
            'longitude' => $lng,
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
