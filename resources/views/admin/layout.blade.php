<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NammaBus Admin — @yield('title', 'Dashboard')</title>
  <link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body        { background-color: #030712; color: white; }
    .sidebar    { background-color: #0f172a; border-right: 1px solid #1e293b; }
    .topbar     { background-color: #0f172a; border-bottom: 1px solid #1e293b; }
    .card       { background-color: #0f172a; border: 1px solid #1e293b; }
    .table-row:hover { background-color: rgba(30,41,59,0.5); }
    .nav-active { background-color: #6200EE; color: white; }
    .nav-item   { color: #94a3b8; }
    .nav-item:hover { background-color: #1e293b; color: white; }
    .input-dark { background-color: #1e293b; border: 1px solid #334155; color: white; }
    .input-dark:focus { outline: none; border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,0.3); }
    .input-dark::placeholder { color: #475569; }
    .btn-purple { background-color: #6200EE; color: white; }
    .btn-purple:hover { background-color: #7c3aed; }
    .btn-red    { background-color: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
    .btn-red:hover { background-color: rgba(239,68,68,0.2); }
    .badge-live    { background-color: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
    .badge-offline { background-color: #1e293b; color: #64748b; border: 1px solid #334155; }
    .badge-delayed { background-color: rgba(249,115,22,0.15); color: #fb923c; border: 1px solid rgba(249,115,22,0.2); }
    .badge-purple  { background-color: rgba(98,0,238,0.15); color: #a78bfa; border: 1px solid rgba(98,0,238,0.2); }
    .badge-teal    { background-color: rgba(20,184,166,0.15); color: #2dd4bf; border: 1px solid rgba(20,184,166,0.2); }
    .divider    { border-color: #1e293b; }
    .select-dark { background-color: #1e293b; border: 1px solid #334155; color: #cbd5e1; }
    .avatar-purple { background-color: rgba(98,0,238,0.2); border: 1px solid rgba(98,0,238,0.3); color: #a78bfa; }
    .avatar-teal   { background-color: rgba(20,184,166,0.15); border: 1px solid rgba(20,184,166,0.25); color: #2dd4bf; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #030712; }
    ::-webkit-scrollbar-thumb { background: #6200EE; border-radius: 2px; }
    .modal-bg { background-color: rgba(0,0,0,0.75); }
    .modal-card { background-color: #0f172a; border: 1px solid #334155; }
  </style>
</head>
<body class="min-h-screen flex">

  <!-- Sidebar -->
  <aside class="sidebar w-64 flex flex-col min-h-screen fixed top-0 left-0 z-50">
    <!-- Logo -->
    <div class="px-6 py-5" style="border-bottom:1px solid #1e293b">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-xl" style="background-color:#6200EE">🚌</div>
        <div>
          <div class="font-bold text-white text-sm">NammaBus</div>
          <div class="text-xs" style="color:#475569">நம்ம பஸ் Admin</div>
        </div>
      </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 px-3 py-5 space-y-1">
      <p class="text-xs font-semibold uppercase tracking-widest px-3 mb-3" style="color:#334155">Menu</p>

      <a href="{{ route('admin.dashboard') }}"
         class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'nav-active' : 'nav-item' }}">
        <span>📊</span><span>Dashboard</span>
      </a>
      <a href="{{ route('admin.buses') }}"
         class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.buses') ? 'nav-active' : 'nav-item' }}">
        <span>🚌</span><span>Buses</span>
      </a>
      <a href="{{ route('admin.drivers') }}"
         class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.drivers') ? 'nav-active' : 'nav-item' }}">
        <span>👨‍✈️</span><span>Drivers</span>
      </a>
      <a href="{{ route('admin.users') }}"
         class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.users') ? 'nav-active' : 'nav-item' }}">
        <span>👥</span><span>Users</span>
      </a>
    </nav>

    <!-- Logout -->
    <div class="px-3 py-4" style="border-top:1px solid #1e293b">
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all" style="color:#f87171">
          <span>🚪</span><span>Logout</span>
        </button>
      </form>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 ml-64 flex flex-col min-h-screen">
    <!-- Topbar -->
    <header class="topbar px-8 py-4 flex items-center justify-between sticky top-0 z-40">
      <div>
        <h1 class="text-lg font-bold text-white">@yield('title', 'Dashboard')</h1>
        <p class="text-xs" style="color:#475569">Tiruchirappalli, Tamil Nadu</p>
      </div>
      <div class="text-xs font-bold px-3 py-1.5 rounded-full" style="background:rgba(98,0,238,0.2);color:#a78bfa;border:1px solid rgba(98,0,238,0.3)">
        Admin
      </div>
    </header>

    <!-- Flash -->
    <div class="px-8 pt-5">
      @if(session('success'))
        <div class="px-4 py-3 rounded-xl text-sm mb-4" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);color:#4ade80">
          ✅ {{ session('success') }}
        </div>
      @endif
      @if(session('error'))
        <div class="px-4 py-3 rounded-xl text-sm mb-4" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#f87171">
          ⚠️ {{ session('error') }}
        </div>
      @endif
    </div>

    <!-- Content -->
    <main class="flex-1 px-8 pb-8 pt-2">@yield('content')</main>

    <footer class="px-8 py-4 text-xs" style="color:#1e293b;border-top:1px solid #0f172a">
      NammaBus Admin v2.0 · நம்ம பஸ் · Tiruchirappalli 🇮🇳
    </footer>
  </div>

</body>
</html>
