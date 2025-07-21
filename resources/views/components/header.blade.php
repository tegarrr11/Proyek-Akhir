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
        <div class="flex items-center gap-2">
            @php
            $avatar = auth()->user()->avatar ?? null;
            @endphp

            @if ($avatar)
            <img src="{{ $avatar }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
            @else
            <div class="bg-[#003366] text-white w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            @endif

            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
        </div>
    </div>