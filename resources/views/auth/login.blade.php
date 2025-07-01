@vite(['resources/css/app.css', 'resources/js/app.js'])
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Peminjaman</title>
</head>

<body class="font-poppins bg-[#fffff] min-h-screen flex items-center justify-center px-4">

  <div class="bg-white p-6 sm:p-8 rounded-xl shadow-md text-center w-full max-w-xs sm:max-w-sm">
    <img src="{{ asset('images/sarpras-logo.png') }}" alt="Logo" class="mx-auto w-40 mb-4">

    <!-- Trigger Button -->
    <button onclick="document.getElementById('roleModal').classList.remove('hidden')"
      class="bg-[#c4f7fd] hover:bg-[#a6e9f4] transition w-full py-2 rounded-5 flex items-center justify-center gap-2 text-xs font-medium text-gray-800">
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 1a9 9 0 100 18 9 9 0 000-18zM9 14H7v-4h2v4zm4 0h-2V6h2v8z" />
      </svg>
      Login with Email
    </button>
    <a
    href="auth/redirect"
    class="mt-3 w-full bg-background text-sky-500 px-4 py-2 rounded-lg shadow-md font-semibold flex gap-3 items-center justify-center hover:bg-[#009ef7] hover:text-white cursor-pointer">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
       <path d="M10 1a9 9 0 100 18 9 9 0 000-18zM9 14H7v-4h2v4zm4 0h-2V6h2v8z" />
     </svg>
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