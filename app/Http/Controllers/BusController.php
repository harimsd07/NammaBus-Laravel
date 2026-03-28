<?php

namespace App\Http\Controllers;

use App\Events\BusLocationUpdated;
use App\Models\BusDetail;
use Illuminate\Http\Request;

class BusController extends Controller
{
    // ── store() ───────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'busNameOrbusNo'         => 'required|string|max:100',
            'vehicle_no'             => 'required|string|max:30',
            'pick_up_stop'           => 'required|string|max:150',
            'destination'            => 'required|string|max:150',
            'pickup_time'            => 'required|date_format:H:i:s',
            'reach_destination_time' => 'required|date_format:H:i:s',
            'latitude'               => 'nullable|numeric|between:-90,90',
            'longitude'              => 'nullable|numeric|between:-180,180',
        ]);

        $busDetail = BusDetail::create($validated);

        return response()->json([
            'status'  => 201,
            'message' => 'Bus registered successfully',
            'data'    => $busDetail,
        ], 201);
    }

    // ── index() ───────────────────────────────────────────────────────────────

    public function index()
    {
        $allBuses = BusDetail::orderBy('pickup_time', 'asc')->get();

        return response()->json([
            'status'  => 200,
            'message' => 'Bus details fetched',
            'count'   => $allBuses->count(),
            'data'    => $allBuses,
        ]);
    }

    // ── Phase 2: myBus() — returns the bus assigned to the logged-in driver ──

    public function myBus(Request $request)
    {
        $bus = BusDetail::where('driver_id', $request->user()->id)->first();

        if (!$bus) {
            return response()->json([
                'status'  => 404,
                'message' => 'No bus assigned yet. Please contact admin.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Assigned bus fetched',
            'data'    => $bus,
        ]);
    }

    // ── Phase 2: assignBus() — admin assigns a driver to a bus ───────────────

    public function assignBus(Request $request)
    {
        $request->validate([
            'bus_id'    => 'required|exists:bus_detail_tables,id',
            'driver_id' => 'required|exists:users,id',
        ]);

        // Unassign this driver from any previous bus first
        BusDetail::where('driver_id', $request->driver_id)
                 ->update(['driver_id' => null]);

        // Assign driver to the new bus
        $bus = BusDetail::findOrFail($request->bus_id);
        $bus->driver_id = $request->driver_id;
        $bus->save();

        return response()->json([
            'status'  => 200,
            'message' => 'Driver assigned to bus successfully',
            'data'    => $bus,
        ]);
    }

    // ── Phase 2: unassignBus() — remove driver from their bus ────────────────

    public function unassignBus(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:bus_detail_tables,id',
        ]);

        $bus = BusDetail::findOrFail($request->bus_id);
        $bus->driver_id = null;
        $bus->save();

        return response()->json([
            'status'  => 200,
            'message' => 'Driver unassigned successfully',
            'data'    => $bus,
        ]);
    }

    // ── updateLocation() — now verifies driver owns this bus ─────────────────

    public function updateLocation(Request $request)
    {
        $request->validate([
            'id'          => 'required|exists:bus_detail_tables,id',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'student_lat' => 'nullable|numeric|between:-90,90',
            'student_lng' => 'nullable|numeric|between:-180,180',
        ]);

        $bus = BusDetail::findOrFail($request->id);

        // Phase 2 fix: verify the authenticated driver owns this bus
        if ($bus->driver_id !== null &&
            $bus->driver_id !== $request->user()->id) {
            return response()->json([
                'status'  => 403,
                'message' => 'You are not assigned to this bus',
            ], 403);
        }

        $bus->latitude  = $request->latitude;
        $bus->longitude = $request->longitude;
        $bus->save();

        $distance = null;
        $isNear   = false;

        if ($request->filled(['student_lat', 'student_lng'])) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $request->student_lat,
                $request->student_lng
            );
            $isNear = $distance <= 0.5;
        }

        event(new BusLocationUpdated($bus));

        return response()->json([
            'status'      => 200,
            'message'     => 'Location updated and broadcasted',
            'is_near'     => $isNear,
            'distance_km' => $distance ? round($distance, 2) : null,
            'data'        => $bus,
        ]);
    }

    // ── update() ──────────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $busDetail = BusDetail::find($id);

        if (!$busDetail) {
            return response()->json(['status' => 404, 'message' => 'Bus not found'], 404);
        }

        $validated = $request->validate([
            'busNameOrbusNo'         => 'sometimes|string|max:100',
            'vehicle_no'             => 'sometimes|string|max:30',
            'pick_up_stop'           => 'sometimes|string|max:150',
            'destination'            => 'sometimes|string|max:150',
            'pickup_time'            => 'sometimes|date_format:H:i:s',
            'reach_destination_time' => 'sometimes|date_format:H:i:s',
        ]);

        $busDetail->update($validated);

        return response()->json([
            'status'  => 200,
            'message' => 'Bus updated successfully',
            'data'    => $busDetail,
        ]);
    }

    // ── delete() ─────────────────────────────────────────────────────────────

    public function delete($id)
    {
        $busDetail = BusDetail::find($id);

        if (!$busDetail) {
            return response()->json(['status' => 404, 'message' => 'Bus not found'], 404);
        }

        $busDetail->delete();

        return response()->json(['status' => 200, 'message' => 'Bus deleted successfully']);
    }

    // ── search() ─────────────────────────────────────────────────────────────

    public function search(Request $request)
    {
        $query = BusDetail::query();

        if ($request->filled('from')) {
            $query->where('pick_up_stop', 'LIKE', "%{$request->from}%");
        }

        if ($request->filled('to')) {
            $query->where('destination', 'LIKE', "%{$request->to}%");
        }

        if ($request->boolean('upcoming')) {
            $query->where('pickup_time', '>=', now()->format('H:i:s'));
        }

        $buses = $query->orderBy('pickup_time', 'asc')->get();

        return response()->json([
            'status' => 200,
            'count'  => $buses->count(),
            'data'   => $buses,
        ]);
    }


    // ── Phase 3: reportDelay() — driver reports a delay ──────────────────────

    public function reportDelay(Request $request)
    {
        $request->validate([
            'id'             => 'required|exists:bus_detail_tables,id',
            'delay_minutes'  => 'required|integer|min:1|max:120',
            'delay_reason'   => 'required|string|max:255',
        ]);

        $bus = BusDetail::findOrFail($request->id);

        // Only the assigned driver can report a delay
        if ($bus->driver_id !== null &&
            $bus->driver_id !== $request->user()->id) {
            return response()->json([
                'status'  => 403,
                'message' => 'You are not assigned to this bus',
            ], 403);
        }

        $bus->delay_minutes     = $request->delay_minutes;
        $bus->delay_reason      = $request->delay_reason;
        $bus->delay_reported_at = now();
        $bus->save();

        // Broadcast delay update so students see it in real time
        event(new \App\Events\BusLocationUpdated($bus));

        return response()->json([
            'status'  => 200,
            'message' => 'Delay reported successfully',
            'data'    => $bus,
        ]);
    }

    // ── Phase 3: clearDelay() — driver clears a previously reported delay ────

    public function clearDelay(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bus_detail_tables,id',
        ]);

        $bus = BusDetail::findOrFail($request->id);

        if ($bus->driver_id !== null &&
            $bus->driver_id !== $request->user()->id) {
            return response()->json([
                'status'  => 403,
                'message' => 'You are not assigned to this bus',
            ], 403);
        }

        $bus->delay_minutes     = null;
        $bus->delay_reason      = null;
        $bus->delay_reported_at = null;
        $bus->save();

        event(new \App\Events\BusLocationUpdated($bus));

        return response()->json([
            'status'  => 200,
            'message' => 'Delay cleared successfully',
            'data'    => $bus,
        ]);
    }

    // ── Haversine ─────────────────────────────────────────────────────────────

    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $R    = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a    = sin($dLat/2)**2 +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;

        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
