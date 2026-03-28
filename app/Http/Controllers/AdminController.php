<?php

namespace App\Http\Controllers;

use App\Models\BusDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ── Login ─────────────────────────────────────────────────────────────────

    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        // Admin password from .env — ADMIN_PANEL_PASSWORD
        $adminPassword = config('app.admin_panel_password', 'admin123');

        if ($request->password === $adminPassword) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Incorrect password. Try again.');
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login');
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $stats = [
            'total_buses'    => BusDetail::count(),
            'active_buses'   => BusDetail::whereNotNull('latitude')
                                         ->where('latitude', '!=', 0)->count(),
            'total_drivers'  => User::where('role', 'driver')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'assigned_buses' => BusDetail::whereNotNull('driver_id')->count(),
            'delayed_buses'  => BusDetail::whereNotNull('delay_minutes')
                                         ->where('delay_minutes', '>', 0)->count(),
        ];

        $recentBuses = BusDetail::with('driver')
                                ->orderBy('updated_at', 'desc')
                                ->limit(5)
                                ->get();

        return view('admin.dashboard', compact('stats', 'recentBuses'));
    }

    // ── Buses ─────────────────────────────────────────────────────────────────

    public function buses()
    {
        $buses   = BusDetail::with('driver')->orderBy('pickup_time')->get();
        $drivers = User::where('role', 'driver')->orderBy('name')->get();
        return view('admin.buses', compact('buses', 'drivers'));
    }

    public function storeBus(Request $request)
    {
        $validated = $request->validate([
            'busNameOrbusNo'         => 'required|string|max:100',
            'vehicle_no'             => 'required|string|max:30',
            'pick_up_stop'           => 'required|string|max:150',
            'destination'            => 'required|string|max:150',
            'pickup_time'            => 'required|date_format:H:i',
            'reach_destination_time' => 'required|date_format:H:i',
        ]);

        // Convert HH:mm to HH:mm:ss
        $validated['pickup_time']            .= ':00';
        $validated['reach_destination_time'] .= ':00';

        BusDetail::create($validated);
        return back()->with('success', 'Bus added successfully.');
    }

    public function updateBus(Request $request, $id)
    {
        $bus = BusDetail::findOrFail($id);

        $validated = $request->validate([
            'busNameOrbusNo'         => 'required|string|max:100',
            'vehicle_no'             => 'required|string|max:30',
            'pick_up_stop'           => 'required|string|max:150',
            'destination'            => 'required|string|max:150',
            'pickup_time'            => 'required|date_format:H:i',
            'reach_destination_time' => 'required|date_format:H:i',
        ]);

        $validated['pickup_time']            .= ':00';
        $validated['reach_destination_time'] .= ':00';

        $bus->update($validated);
        return back()->with('success', 'Bus updated successfully.');
    }

    public function deleteBus($id)
    {
        BusDetail::findOrFail($id)->delete();
        return back()->with('success', 'Bus deleted.');
    }

    // ── Drivers ───────────────────────────────────────────────────────────────

    public function drivers()
    {
        $drivers = User::where('role', 'driver')->orderBy('name')->get();
        $buses   = BusDetail::orderBy('busNameOrbusNo')->get();
        return view('admin.drivers', compact('drivers', 'buses'));
    }

    public function assignBus(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'bus_id'    => 'required|exists:bus_detail_tables,id',
        ]);

        // Unassign driver from any current bus
        BusDetail::where('driver_id', $request->driver_id)
                 ->update(['driver_id' => null]);

        // Assign to new bus
        BusDetail::findOrFail($request->bus_id)
                 ->update(['driver_id' => $request->driver_id]);

        return back()->with('success', 'Driver assigned successfully.');
    }

    public function unassignBus(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        BusDetail::where('driver_id', $request->driver_id)
                 ->update(['driver_id' => null]);

        return back()->with('success', 'Driver unassigned.');
    }

    // ── Users ─────────────────────────────────────────────────────────────────

    public function users()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.users', compact('users'));
    }
}
