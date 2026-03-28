<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NammaBus Admin</title>
  <link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body { background-color: #030712; }
    .card { background-color: #111827; border: 1px solid #1f2937; }
    .input-dark { background-color: #1f2937; border: 1px solid #374151; color: white; }
    .input-dark:focus { outline: none; border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,0.3); }
    .btn-purple { background-color: #6200EE; }
    .btn-purple:hover { background-color: #7c3aed; }
    .input-dark::placeholder { color: #4b5563; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">

  <div class="w-full max-w-md mx-4">
    <!-- Logo -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl mb-4" style="background-color:#6200EE">
        <span class="text-4xl">🚌</span>
      </div>
      <h1 class="text-3xl font-bold text-white tracking-tight">NammaBus</h1>
      <p class="text-gray-500 text-sm mt-1">நம்ம பஸ் — Admin Panel</p>
    </div>

    <!-- Card -->
    <div class="card rounded-2xl p-8">
      <h2 class="text-lg font-bold text-white mb-1">Welcome back</h2>
      <p class="text-gray-500 text-sm mb-6">Enter your admin password to continue.</p>

      @if(session('error'))
        <div class="bg-red-900 border border-red-700 text-red-300 px-4 py-3 rounded-xl mb-5 text-sm">
          ⚠️ {{ session('error') }}
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="mb-5">
          <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-wider">Admin Password</label>
          <input type="password" name="password"
            class="input-dark w-full px-4 py-3 rounded-xl text-sm"
            placeholder="Enter your password" autofocus required>
        </div>
        <button type="submit" class="btn-purple w-full text-white font-bold py-3 rounded-xl text-sm transition-colors">
          Login to Admin Panel →
        </button>
      </form>
    </div>

    <p class="text-center text-xs text-gray-700 mt-6">
      NammaBus v2.0 · Tiruchirappalli, Tamil Nadu 🇮🇳
    </p>
  </div>

</body>
</html>
