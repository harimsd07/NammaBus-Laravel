@extends('admin.layout')
@section('title', 'Buses')
@section('content')

  <!-- Add Bus -->
  <div class="card rounded-2xl p-6 mb-6">
    <h2 class="font-bold text-white mb-1">Add New Bus</h2>
    <p class="text-sm mb-5" style="color:#475569">Register a new bus route to enable live tracking.</p>
    <form method="POST" action="{{ route('admin.buses.store') }}">
      @csrf
      <div class="grid grid-cols-2 gap-4">
        @foreach([
          ['busNameOrbusNo','Bus Name / Number','e.g. Route 1 - Express','text'],
          ['vehicle_no','Vehicle Registration','e.g. TN 45 AB 1234','text'],
          ['pick_up_stop','Starting Point','e.g. Central Bus Stand','text'],
          ['destination','Destination','e.g. Trichy Junction','text'],
        ] as [$name,$label,$ph,$type])
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#475569">{{ $label }}</label>
          <input type="{{ $type }}" name="{{ $name }}" required placeholder="{{ $ph }}"
            class="input-dark w-full px-4 py-2.5 rounded-xl text-sm">
        </div>
        @endforeach
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#475569">Departure Time</label>
          <input type="time" name="pickup_time" required class="input-dark w-full px-4 py-2.5 rounded-xl text-sm">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#475569">Arrival Time</label>
          <input type="time" name="reach_destination_time" required class="input-dark w-full px-4 py-2.5 rounded-xl text-sm">
        </div>
      </div>
      <div class="mt-5">
        <button type="submit" class="btn-purple font-bold px-6 py-2.5 rounded-xl text-sm transition-colors">+ Add Bus</button>
      </div>
    </form>
  </div>

  <!-- Table -->
  <div class="card rounded-2xl overflow-hidden">
    <div class="px-6 py-4" style="border-bottom:1px solid #1e293b">
      <h2 class="font-bold text-white">All Buses <span class="text-sm font-normal" style="color:#475569">({{ $buses->count() }})</span></h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr style="border-bottom:1px solid #1e293b">
            @foreach(['Bus','Route','Times','Driver','Status','Actions'] as $h)
              <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#475569">{{ $h }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @forelse($buses as $bus)
            <tr class="table-row" style="border-bottom:1px solid rgba(30,41,59,0.4)">
              <td class="px-6 py-4">
                <div class="font-semibold text-white text-sm">{{ $bus->busNameOrbusNo }}</div>
                <div class="text-xs mt-0.5" style="color:#475569">{{ $bus->vehicle_no }}</div>
              </td>
              <td class="px-6 py-4 text-sm" style="color:#94a3b8">
                {{ $bus->pick_up_stop }} <span style="color:#334155">→</span> {{ $bus->destination }}
              </td>
              <td class="px-6 py-4 text-sm font-mono" style="color:#cbd5e1">
                {{ substr($bus->pickup_time,0,5) }} – {{ substr($bus->reach_destination_time,0,5) }}
              </td>
              <td class="px-6 py-4 text-sm">
                @if($bus->driver)
                  <div class="font-medium text-white">{{ $bus->driver->name }}</div>
                  <div class="text-xs mt-0.5" style="color:#475569">{{ $bus->driver->email }}</div>
                @else
                  <span style="color:#475569;font-style:italic">No driver</span>
                @endif
              </td>
              <td class="px-6 py-4">
                @if($bus->delay_minutes)
                  <span class="badge-delayed text-xs font-bold px-2.5 py-1 rounded-full">⚠ Delayed</span>
                @elseif($bus->latitude && $bus->latitude != 0)
                  <span class="badge-live text-xs font-bold px-2.5 py-1 rounded-full">● Live</span>
                @else
                  <span class="badge-offline text-xs font-bold px-2.5 py-1 rounded-full">○ Offline</span>
                @endif
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <button onclick="openEdit({{ $bus->id }},'{{ addslashes($bus->busNameOrbusNo) }}','{{ $bus->vehicle_no }}','{{ addslashes($bus->pick_up_stop) }}','{{ addslashes($bus->destination) }}','{{ substr($bus->pickup_time,0,5) }}','{{ substr($bus->reach_destination_time,0,5) }}')"
                    class="badge-purple text-xs font-medium px-3 py-1.5 rounded-lg transition-colors cursor-pointer">Edit</button>
                  <form method="POST" action="{{ route('admin.buses.delete', $bus->id) }}" onsubmit="return confirm('Delete {{ $bus->busNameOrbusNo }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-red text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-6 py-12 text-center" style="color:#334155">No buses yet. Add one above.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="fixed inset-0 modal-bg hidden items-center justify-center z-50">
    <div class="modal-card rounded-2xl p-8 w-full max-w-lg mx-4">
      <h3 class="font-bold text-lg text-white mb-1">Edit Bus</h3>
      <p class="text-sm mb-5" style="color:#475569">Changes reflect for students immediately.</p>
      <form id="editForm" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#475569">Bus Name</label>
            <input id="e_name" type="text" name="busNameOrbusNo" required class="input-dark w-full px-4 py-2.5 rounded-xl text-sm">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#475569">Vehicle No.</label>
            <input id="e_vehicle" type="text" name="vehicle_no" required class="input-dark w-full px-4 py-2.5 rounded-xl text-sm">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#475569">Starting Point</label>
            <input id="e_from" type="text" name="pick_up_stop" required class="input-dark w-full px-4 py-2.5 rounded-xl text-sm">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#475569">Destination</label>
            <input id="e_to" type="text" name="destination" required class="input-dark w-full px-4 py-2.5 rounded-xl text-sm">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#475569">Departure</label>
            <input id="e_dep" type="time" name="pickup_time" required class="input-dark w-full px-4 py-2.5 rounded-xl text-sm">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#475569">Arrival</label>
            <input id="e_arr" type="time" name="reach_destination_time" required class="input-dark w-full px-4 py-2.5 rounded-xl text-sm">
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button type="submit" class="btn-purple flex-1 font-bold py-2.5 rounded-xl text-sm">Save Changes</button>
          <button type="button" onclick="closeEdit()" class="flex-1 font-bold py-2.5 rounded-xl text-sm" style="background:#1e293b;color:#94a3b8">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    function openEdit(id,name,vehicle,from,to,dep,arr){
      document.getElementById('editForm').action=`/admin/buses/${id}`;
      document.getElementById('e_name').value=name;
      document.getElementById('e_vehicle').value=vehicle;
      document.getElementById('e_from').value=from;
      document.getElementById('e_to').value=to;
      document.getElementById('e_dep').value=dep;
      document.getElementById('e_arr').value=arr;
      const m=document.getElementById('editModal');
      m.classList.remove('hidden');m.classList.add('flex');
    }
    function closeEdit(){
      const m=document.getElementById('editModal');
      m.classList.add('hidden');m.classList.remove('flex');
    }
  </script>

@endsection
