@props([
    'title' => 'Dashboard', 
    'breadcrumb' => 'Dashboard > Ruangan'
])

<div class="flex items-center justify-between mb-6">
    <!-- Judul dan breadcrumb -->
    <div>
        <h1 class="text-md sm:text-lg font-semibold text-gray-800">{{ $title }}</h1>
        <p class="text-sm text-gray-500">{{ $breadcrumb }}</p>
    </div>

    <!-- User Info -->
    <div class="flex items-center gap-4">
        <button class="text-gray-500 hover:text-black">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
</svg>

        </button>

        <div class="flex items-center gap-2">
            <div class="bg-[#003366] text-white w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
        </div>
    </div>
</div>
