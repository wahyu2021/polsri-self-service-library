<?php

namespace App\Services;

use App\Interfaces\LogbookRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

/**
 * Service for handling library attendance scanning and geofencing validation.
 */
class ScanService
{
    /**
     * @var LogbookRepositoryInterface
     */
    protected LogbookRepositoryInterface $logbookRepository;

    /**
     * Default Library Coordinates (Gedung A Polsri).
     */
    const DEFAULT_LAT = -2.9850;
    const DEFAULT_LNG = 104.7320;
    const MAX_DISTANCE_METERS = 50; 
    const VALID_QR_CODE = 'LIB-POLSRI-ACCESS';

    /**
     * @param LogbookRepositoryInterface $logbookRepository
     */
    public function __construct(LogbookRepositoryInterface $logbookRepository)
    {
        $this->logbookRepository = $logbookRepository;
    }

    /**
     * Process user check-in with QR code and GPS validation.
     *
     * @param User $user The authenticated user.
     * @param string $qrCode The scanned QR code content.
     * @param float $lat The user's latitude.
     * @param float $lng The user's longitude.
     * @return Model The created logbook entry.
     * @throws \Exception If QR code is invalid or user is outside the library radius.
     */
    public function processCheckIn(User $user, string $qrCode, float $lat, float $lng): Model
    {
        if ($qrCode !== self::VALID_QR_CODE) {
            throw new \Exception('QR Code tidak valid.');
        }

        $libraryLat = (float) Setting::where('key', 'library_lat')->value('value') ?? self::DEFAULT_LAT;
        $libraryLng = (float) Setting::where('key', 'library_lng')->value('value') ?? self::DEFAULT_LNG;

        $distance = $this->calculateDistance($lat, $lng, $libraryLat, $libraryLng);
        
        if ($distance > self::MAX_DISTANCE_METERS) {
             throw new \Exception("Anda berada di luar jangkauan (Jarak: " . round($distance) . "m). Max: " . self::MAX_DISTANCE_METERS . "m.");
        }

        return $this->logbookRepository->create([
            'user_id' => $user->id,
            'check_in_time' => Carbon::now(),
            'latitude' => $lat,
            'longitude' => $lng,
        ]);
    }

    /**
     * Calculate the distance between two coordinates using the Haversine formula.
     *
     * @param float $lat1 Latitude of point 1.
     * @param float $lon1 Longitude of point 1.
     * @param float $lat2 Latitude of point 2.
     * @param float $lon2 Longitude of point 2.
     * @return float Distance in meters.
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}