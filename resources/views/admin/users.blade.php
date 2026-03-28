@extends('admin.layout')
@section('title', 'Users')
@section('content')

  <div class="card rounded-2xl overflow-hidden">
    <div class="px-6 py-4 flex items-center justify-between" style="border-bottom:1px solid #1e293b">
      <h2 class="font-bold text-white">All Users <span class="text-sm font-normal" style="color:#475569">({{ $users->count() }})</span></h2>
      <div class="flex gap-4 text-xs" style="color:#475569">
        <span class="flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full inline-block" style="background:#2dd4bf"></span>
          Students: {{ $users->where('role','student')->count() }}
        </span>
        <span class="flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full inline-block" style="background:#a78bfa"></span>
          Drivers: {{ $users->where('role','driver')->count() }}
        </span>
      </div>
    </div>
    <table class="w-full">
      <thead>
        <tr style="border-bottom:1px solid #1e293b">
          @foreach(['User','Email','Role','Joined'] as $h)
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#475569">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
          <tr class="table-row" style="border-bottom:1px solid rgba(30,41,59,0.4)">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-sm {{ $user->role === 'driver' ? 'avatar-purple' : 'avatar-teal' }}">
                  {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <div>
                  <div class="font-semibold text-white text-sm">{{ $user->name }}</div>
                  <div class="text-xs mt-0.5" style="color:#334155">#{{ $user->id }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm" style="color:#94a3b8">{{ $user->email }}</td>
            <td class="px-6 py-4">
              @if($user->role === 'driver')
                <span class="badge-purple text-xs font-bold px-3 py-1.5 rounded-full">👨‍✈️ Driver</span>
              @else
                <span class="badge-teal text-xs font-bold px-3 py-1.5 rounded-full">🎓 Student</span>
              @endif
            </td>
            <td class="px-6 py-4 text-sm" style="color:#475569">
              {{ $user->created_at->format('d M Y') }}
              <div class="text-xs mt-0.5" style="color:#334155">{{ $user->created_at->diffForHumans() }}</div>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="px-6 py-12 text-center" style="color:#334155">No users yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

@endsection
