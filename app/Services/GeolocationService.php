<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeolocationService
{
    protected $apiKey;

    public function __construct()
    {
        // Use Google Maps Geocoding API or any other geocoding service
        $this->apiKey = env('GOOGLE_MAPS_API_KEY');
    }

    /**
     * Get coordinates from an address
     */
    public function getCoordinates($address)
    {
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $this->apiKey,
            ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                $result = $response->json('results.0');
                $location = $result['geometry']['location'];

                // Extract Country
                $country = null;
                foreach ($result['address_components'] as $component) {
                    if (in_array('country', $component['types'])) {
                        $country = $component['long_name'];
                        break;
                    }
                }

                // Strict Malaysia Filter
                if ($country !== 'Malaysia') {
                    \Log::info("Geolocation: Skipped event location '$address' - Country is '$country'");
                    return null;
                }

                return [
                    'latitude' => $location['lat'],
                    'longitude' => $location['lng'],
                    'formatted_address' => $result['formatted_address'],
                    'country' => $country
                ];
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Geocoding error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get address from coordinates
     */
    public function getAddress($latitude, $longitude)
    {
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => "{$latitude},{$longitude}",
                'key' => $this->apiKey,
            ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                return $response->json('results.0.formatted_address');
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Reverse geocoding error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Calculate distance between two points (in kilometers)
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // kilometers

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }
}