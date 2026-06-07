<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeolocationService;
use Illuminate\Http\Request;

class GeolocationController extends Controller
{
    protected $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    public function geocode(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
        ]);

        $result = $this->geolocationService->getCoordinates($request->address);

        if ($result) {
            return response()->json($result);
        }

        return response()->json(['error' => 'Unable to geocode address'], 404);
    }

    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $address = $this->geolocationService->getAddress(
            $request->latitude,
            $request->longitude
        );

        if ($address) {
            return response()->json(['address' => $address]);
        }

        return response()->json(['error' => 'Unable to get address'], 404);
    }
}
