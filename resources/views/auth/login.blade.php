@vite(['resources/css/app.css', 'resources/js/app.js'])
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Peminjaman</title>
  @include('layouts.partials.head')
  @stack('head')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if(Auth::check())
    <meta name="user-id" content="{{ Auth::id() }}">
  @endif
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#fffff] min-h-screen flex items-center justify-center px-4">

  <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md text-center w-full max-w-xs sm:max-w-sm">
    <img src="{{ asset('images/sarpras-logo.png') }}" alt="Logo" class="mx-auto w-40 mb-4">

    <!-- Trigger Button -->
    <button onclick="document.getElementById('roleModal').classList.remove('hidden')"
      class="bg-[#c4f7fd] hover:bg-[#a6e9f4] transition w-full py-2 rounded-5 flex items-center justify-center gap-2 text-xs font-medium text-gray-800">
      <svg xmlns="http://www.w3.org/2000/svg" width="23.46px" height="24px" viewBox="0 0 256 262"><path fill="#4285f4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622l38.755 30.023l2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"/><path fill="#34a853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055c-34.523 0-63.824-22.773-74.269-54.25l-1.531.13l-40.298 31.187l-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"/><path fill="#fbbc05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82c0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602z"/><path fill="#eb4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0C79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"/></svg>
      Login with Email (Quick Log)
    </button>
    <a
    href="auth/redirect"
    class="bg-[#c4f7fd] hover:bg-[#a6e9f4] transition w-full py-2 rounded-12 mt-3 flex items-center justify-center gap-2 text-xs font-medium text-gray-800">
      <svg xmlns="http://www.w3.org/2000/svg" width="23.46px" height="24px" viewBox="0 0 256 262"><path fill="#4285f4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622l38.755 30.023l2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"/><path fill="#34a853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055c-34.523 0-63.824-22.773-74.269-54.25l-1.531.13l-40.298 31.187l-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"/><path fill="#fbbc05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82c0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602z"/><path fill="#eb4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0C79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"/></svg>
        <div>Sign in with Google</div>
    </a>
  </div>

  

  <!-- Modal Role -->
  <div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center px-4">
    <div class="bg-white p-6 rounded-lg w-full max-w-xs space-y-3 text-center">
      <h2 class="text-base font-semibold">Login sebagai:</h2>
      <a href="{{ url('/quick-login/admin') }}" class="block w-full py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Admin</a>
      <a href="{{ url('/quick-login/mahasiswa') }}" class="block w-full py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Mahasiswa</a>
      <a href="{{ url('/quick-login/bem') }}" class="block w-full py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">BEM</a>
      <a href="{{ url('/quick-login/dosen') }}" class="block w-full py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Dosen</a>
      <button onclick="document.getElementById('roleModal').classList.add('hidden')"
        class="block w-full py-2 text-gray-600 hover:text-black text-sm">Batal</button>
    </div>
  </div>

</body>

</html>