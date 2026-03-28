@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')

  <!-- Stats Grid -->
  <div class="grid grid-cols-3 gap-5 mb-8">
    <div class="card rounded-2xl p-5">
      <div class="flex items-center justify-between mb-3">
        <span class="text-2xl">🚌</span>
        <span class="text-xs font-semibold uppercase tracking-wider" style="color:#334155">Total</span>
      </div>
      <div class="text-3xl font-bold text-white">{{ $stats['total_buses'] }}</div>
      <div class="text-sm mt-1" style="color:#475569">Buses registered</div>
    </div>
    <div class="card rounded-2xl p-5">
      <div class="flex items-center justify-between mb-3">
        <span class="text-2xl">📡</span>
        <span class="text-xs font-semibold uppercase tracking-wider" style="color:#16a34a">Live</span>
      </div>
      <div class="text-3xl font-bold" style="color:#4ade80">{{ $stats['active_buses'] }}</div>
      <div class="text-sm mt-1" style="color:#475569">Broadcasting GPS</div>
    </div>
    <div class="card rounded-2xl p-5">
      <div class="flex items-center justify-between mb-3">
        <span class="text-2xl">✅</span>
        <span class="text-xs font-semibold uppercase tracking-wider" style="color:#6200EE">Assigned</span>
      </div>
      <div class="text-3xl font-bold" style="color:#a78bfa">{{ $stats['assigned_buses'] }}</div>
      <div class="text-sm mt-1" style="color:#475569">Buses with driver</div>
    </div>
    <div class="card rounded-2xl p-5">
      <div class="flex items-center justify-between mb-3">
        <span class="text-2xl">👨‍✈️</span>
        <span class="text-xs font-semibold uppercase tracking-wider" style="color:#334155">Drivers</span>
      </div>
      <div class="text-3xl font-bold text-white">{{ $stats['total_drivers'] }}</div>
      <div class="text-sm mt-1" style="color:#475569">Registered drivers</div>
    </div>
    <div class="card rounded-2xl p-5">
      <div class="flex items-center justify-between mb-3">
        <span class="text-2xl">🎓</span>
        <span class="text-xs font-semibold uppercase tracking-wider" style="color:#334155">Students</span>
      </div>
      <div class="text-3xl font-bold text-white">{{ $stats['total_students'] }}</div>
      <div class="text-sm mt-1" style="color:#475569">Registered students</div>
    </div>
    <div class="card rounded-2xl p-5">
      <div class="flex items-center justify-between mb-3">
        <span class="text-2xl">⚠️</span>
        <span class="text-xs font-semibold uppercase tracking-wider" style="color:#c2410c">Delayed</span>
      </div>
      <div class="text-3xl font-bold" style="color:{{ $stats['delayed_buses'] > 0 ? '#fb923c' : '#334155' }}">
        {{ $stats['delayed_buses'] }}
      </div>
      <div class="text-sm mt-1" style="color:#475569">Buses with delay</div>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="card rounded-2xl overflow-hidden">
    <div class="px-6 py-4 flex items-center justify-between" style="border-bottom:1px solid #1e293b">
      <h2 class="font-bold text-white">Recent Bus Activity</h2>
      <a href="{{ route('admin.buses') }}" class="text-sm font-medium" style="color:#a78bfa">View all →</a>
    </div>
    <table class="w-full">
      <thead>
        <tr style="border-bottom:1px solid #1e293b">
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#475569">Bus</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#475569">Route</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#475569">Driver</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#475569">Status</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#475569">Updated</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentBuses as $bus)
          <tr class="table-row" style="border-bottom:1px solid rgba(30,41,59,0.5)">
            <td class="px-6 py-4">
              <div class="font-medium text-white text-sm">{{ $bus->busNameOrbusNo }}</div>
              <div class="text-xs mt-0.5" style="color:#475569">{{ $bus->vehicle_no }}</div>
            </td>
            <td class="px-6 py-4 text-sm" style="color:#94a3b8">
              {{ $bus->pick_up_stop }} <span style="color:#334155">→</span> {{ $bus->destination }}
            </td>
            <td class="px-6 py-4 text-sm">
              @if($bus->driver)
                <span class="font-medium text-white">{{ $bus->driver->name }}</span>
              @else
                <span style="color:#475569;font-style:italic">Unassigned</span>
              @endif
            </td>
            <td class="px-6 py-4">
              @if($bus->delay_minutes)
                <span class="badge-delayed text-xs font-bold px-2.5 py-1 rounded-full">⚠ Delayed {{ $bus->delay_minutes }}m</span>
              @elseif($bus->latitude && $bus->latitude != 0)
                <span class="badge-live text-xs font-bold px-2.5 py-1 rounded-full">● Live</span>
              @else
                <span class="badge-offline text-xs font-bold px-2.5 py-1 rounded-full">○ Offline</span>
              @endif
            </td>
            <td class="px-6 py-4 text-xs" style="color:#475569">{{ $bus->updated_at->diffForHumans() }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-6 py-12 text-center" style="color:#334155">No buses yet. <a href="{{ route('admin.buses') }}" style="color:#a78bfa">Add one →</a></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

@endsection
