<?php

namespace App\Http\Controllers;

use App\Events\BusLocationUpdated;
use App\Models\BusDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    public function store(Request $request)
    {
        $busDetail = new BusDetail;
        $busDetail->busNameOrbusNo = $request->busNameOrbusNo;
        $busDetail->vehicle_no = $request->vehicle_no;
        $busDetail->pick_up_stop = $request->pick_up_stop;
        $busDetail->destination = $request->destination;
        $busDetail->pickup_time = $request->pickup_time;
        $busDetail->reach_destination_time = $request->reach_destination_time;
        $busDetail->save();

        return response()->json([
            'status' => 200,
            'message' => 'data stored successfully',
        ]);
    }

    public function index()
    {
        $allBuses = BusDetail::all();

        return response()->json([
            'status' => 200,
            'message' => 'Bus Details Fetched',
            'count' => $allBuses->count(),
            'data' => $allBuses
        ]);
    }

    /**
     * Update live location and calculate distance for alerts
     * Logic Added: Improved validation to match your specific table structure
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bus_detail_tables,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'student_lat' => 'nullable|numeric',
            'student_lng' => 'nullable|numeric',
        ]);

        $bus = BusDetail::find($request->id);
        $bus->latitude = $request->latitude;
        $bus->longitude = $request->longitude;
        $bus->save();

        $distance = null;
        $isNear = false;

        // Logic: Calculate distance if student coordinates are provided from the Flutter app
        if ($request->has(['student_lat', 'student_lng'])) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $request->student_lat,
                $request->student_lng
            );

            // Logic: Threshold for alert - 500 meters
            $isNear = $distance <= 0.5;
        }

        // Logic Added: Broadcast the update so the Flutter map moves in real-time
        if (class_exists('App\Events\BusLocationUpdated')) {
            event(new BusLocationUpdated($bus));
        }

        return response()->json([
            'status' => 200,
            'message' => 'Location updated and broadcasted',
            'is_near' => $isNear,
            'distance_km' => $distance ? round($distance, 2) : null,
            'data' => $bus
        ]);
    }

    /**
     * Haversine Formula to calculate distance between two points
     * Logic: Mathematical formula to find distance between two GPS coordinates
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the earth in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function update(Request $request, $id)
    {
        $busDetail = BusDetail::find($id);
        if (!$busDetail) return response()->json(['status' => 404, 'message' => 'Not Found']);

        $busDetail->busNameOrbusNo = $request->busNameOrbusNo;
        $busDetail->vehicle_no = $request->vehicle_no;
        $busDetail->pick_up_stop = $request->pick_up_stop;
        $busDetail->destination = $request->destination;
        $busDetail->pickup_time = $request->pickup_time;
        $busDetail->reach_destination_time = $request->reach_destination_time;
        $busDetail->save();

        return response()->json([
            'status' => 200,
            'message' => 'data updated successfully',
        ]);
    }

    public function delete($id)
    {
        $deleteDetail = BusDetail::find($id);
        if ($deleteDetail) {
            $deleteDetail->delete();
            return response()->json(['status' => 200, 'message' => 'Deleted']);
        }
        return response()->json(['status' => 404, 'message' => 'Not Found']);
    }

    /**
     * Logic: Finds upcoming buses based on current time
     */
    public function search(Request $request)
    {
        $currentTime = now()->format('H:i:s');

        $buses = BusDetail::where('pick_up_stop', 'LIKE', "%{$request->from}%")
            ->where('destination', 'LIKE', "%{$request->to}%")
            ->where('pickup_time', '>=', $currentTime)
            ->orderBy('pickup_time', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $buses
        ]);
    }
}
