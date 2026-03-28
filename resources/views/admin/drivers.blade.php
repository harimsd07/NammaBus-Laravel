@extends('admin.layout')
@section('title', 'Drivers')
@section('content')

  <div class="card rounded-2xl overflow-hidden">
    <div class="px-6 py-4 flex items-center justify-between" style="border-bottom:1px solid #1e293b">
      <h2 class="font-bold text-white">All Drivers <span class="text-sm font-normal" style="color:#475569">({{ $drivers->count() }})</span></h2>
      <span class="text-xs" style="color:#334155">Assign a bus to enable GPS broadcasting</span>
    </div>
    <table class="w-full">
      <thead>
        <tr style="border-bottom:1px solid #1e293b">
          @foreach(['Driver','Email','Assigned Bus','Actions'] as $h)
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#475569">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @forelse($drivers as $driver)
          @php $assignedBus = $buses->firstWhere('driver_id', $driver->id); @endphp
          <tr class="table-row" style="border-bottom:1px solid rgba(30,41,59,0.4)">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-sm avatar-purple">
                  {{ strtoupper(substr($driver->name,0,1)) }}
                </div>
                <div>
                  <div class="font-semibold text-white text-sm">{{ $driver->name }}</div>
                  <div class="text-xs mt-0.5" style="color:#475569">Driver</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm" style="color:#94a3b8">{{ $driver->email }}</td>
            <td class="px-6 py-4">
              @if($assignedBus)
                <div class="font-semibold text-white text-sm">{{ $assignedBus->busNameOrbusNo }}</div>
                <div class="text-xs mt-0.5" style="color:#475569">{{ $assignedBus->vehicle_no }}</div>
              @else
                <span class="badge-offline text-xs font-bold px-2.5 py-1 rounded-full">No bus assigned</span>
              @endif
            </td>
            <td class="px-6 py-4">
              <div class="flex gap-2 items-center flex-wrap">
                <form method="POST" action="{{ route('admin.assign') }}" class="flex gap-2 items-center">
                  @csrf
                  <input type="hidden" name="driver_id" value="{{ $driver->id }}">
                  <select name="bus_id" class="select-dark rounded-xl px-3 py-2 text-xs">
                    <option value="">— Select bus —</option>
                    @foreach($buses as $bus)
                      <option value="{{ $bus->id }}" {{ $assignedBus && $assignedBus->id == $bus->id ? 'selected' : '' }}>
                        {{ $bus->busNameOrbusNo }}
                      </option>
                    @endforeach
                  </select>
                  <button type="submit" class="btn-purple text-xs font-bold px-4 py-2 rounded-xl">Assign</button>
                </form>
                @if($assignedBus)
                  <form method="POST" action="{{ route('admin.unassign') }}" onsubmit="return confirm('Unassign {{ $driver->name }}?')">
                    @csrf
                    <input type="hidden" name="driver_id" value="{{ $driver->id }}">
                    <button type="submit" class="btn-red text-xs font-bold px-4 py-2 rounded-xl">Unassign</button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="px-6 py-12 text-center" style="color:#334155">No drivers registered yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

@endsection
