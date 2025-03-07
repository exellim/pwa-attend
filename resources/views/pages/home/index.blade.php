@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center w-full px-4">
        <div class="m-4 w-full max-w-md">
            <div class="credit-card w-full shadow-lg mx-auto rounded-xl bg-white" x-data="creditCard">
                <header class="flex flex-col justify-center items-center px-5 py-5">
                    <div
                        class="relative w-full h-56 bg-gradient-to-r from-blue-700 to-red-500 text-white rounded-xl shadow-lg px-6">
                        <!-- Company Logo -->
                        <div class="absolute top-5 right-5">
                            <img src="{{ asset('images/logos/only logo.png') }}" class="w-[60px]" alt="Company Logo">
                        </div>

                        <!-- Image & Text Container -->
                        <div class="absolute top-14 left-5 flex items-center space-x-3">
                            <!-- Avatar Image -->
                            <img src="{{ Avatar::create($user->name) }}" class="w-[60px] h-[60px] rounded-full"
                                alt="User Avatar">

                            <!-- Text Content (Aligned Right) -->
                            <div class="flex flex-col text-white">
                                <b class="text-lg">{{ $user->name }}</b>
                                <b class="text-sm text-gray-300">{{ $user->nip }}</b>
                            </div>
                        </div>

                        <!-- Live Updating Time -->
                        <div class="absolute bottom-10 left-6 text-lg tracking-widest font-mono">
                            <span id="current-time"></span>
                        </div>

                        <!-- Clock Status -->
                        <div class="absolute bottom-5 left-6 text-sm font-semibold uppercase">
                            {{ $clockInStatus }}
                        </div>

                    </div>
                </header>

                @php
                    $currentDay = now()->format('l'); // Get the current day (Monday, Tuesday, etc.)
                    $clockInTime = $clockInTime ?? null; // Assuming $clockInTime holds the clock-in timestamp
                    $hoursSinceClockIn = $clockInTime ? now()->diffInHours($clockInTime) : 0;
                    $isSaturday = $currentDay === 'Saturday';
                    $canClockOut =
                        $hasClockedIn &&
                        !$hasClockedOut &&
                        (($isSaturday && now()->hour >= 1) || (!$isSaturday && $hoursSinceClockIn >= 8));
                @endphp

                <div class="grid grid-cols-2 gap-4 px-5 py-4 pb-6">
                    <!-- Clock In Button -->
                    <a class="group relative inline-block overflow-hidden border border-green-400 px-8 py-3 focus:ring-2 focus:ring-green-400 focus:outline-none
        {{ $hasClockedIn ? 'cursor-not-allowed opacity-50' : '' }}"
                        href="{{ $hasClockedIn ? '#' : route('attend.clockIn') }}">
                        <span
                            class="absolute inset-y-0 left-0 w-[2px] bg-green-600 transition-all group-hover:w-full"></span>
                        <span class="relative text-sm font-medium text-green-600 transition-colors group-hover:text-white">
                            {{ $hasClockedIn ? 'Already Clocked In' : 'Clock In' }}
                        </span>
                    </a>

                    <!-- Clock Out Button -->
                    <a class="group relative inline-block overflow-hidden border border-blue-400 px-8 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none
        {{ !$canClockOut ? 'cursor-not-allowed opacity-50' : '' }}"
                        href="{{ $canClockOut ? route('attend.clockOut') : '#' }}">
                        <span
                            class="absolute inset-y-0 left-0 w-[2px] bg-blue-600 transition-all group-hover:w-full"></span>
                        <span class="relative text-sm font-medium text-blue-600 transition-colors group-hover:text-white">
                            {{ $hasClockedOut ? 'Already Clocked Out' : 'Clock Out' }}
                        </span>
                    </a>

                    <!-- Overtime In -->
                    <a class="group relative inline-block overflow-hidden border border-green-400 px-8 py-3 focus:ring-2 focus:ring-green-400 focus:outline-none
        {{ !$hasClockedOut ? 'cursor-not-allowed opacity-50' : '' }}"
                        href="{{ $hasClockedOut ? '#oin' : '#' }}">
                        <span
                            class="absolute inset-y-0 left-0 w-[2px] bg-green-600 transition-all group-hover:w-full"></span>
                        <span class="relative text-sm font-medium text-green-600 transition-colors group-hover:text-white">
                            Overtime In
                        </span>
                    </a>

                    <!-- Overtime Out -->
                    <a class="group relative inline-block overflow-hidden border border-blue-400 px-8 py-3 focus:ring-2 focus:ring-blue-400 focus:outline-none
        {{ !$hasClockedOut ? 'cursor-not-allowed opacity-50' : '' }}"
                        href="{{ $hasClockedOut ? '#out' : '#' }}">
                        <span
                            class="absolute inset-y-0 left-0 w-[2px] bg-blue-600 transition-all group-hover:w-full"></span>
                        <span class="relative text-sm font-medium text-blue-600 transition-colors group-hover:text-white">
                            Overtime Out
                        </span>
                    </a>
                </div>


            </div>
        </div>
    </div>

    <!-- Scrollable Sections -->
    <div class="flex flex-col items-center justify-center w-full px-4 pt-8">
        <div class="w-full max-w-md pb-8">
            <div class="w-full shadow-lg mx-auto rounded-xl bg-white px-4 py-4">
                <h1 class="font-bold pb-2 mt-0">Absen</h1>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-1 lg:gap-8 max-h-64 overflow-y-auto py-2 px-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200 rounded-md"
                    x-data="{ openModal: null, modalImage: '' }">

                    @forelse ($clockedInUsers as $index => $clockin)
                        <div x-data="{ open: false }" class="relative">
                            <!-- Clickable User Item -->
                            <div @click="openModal = {{ $index }}; modalImage = '{{ asset('storage/' . $clockin->attendances[0]->clock_in_photo) }}';"
                                class="flex items-center space-x-3 bg-gray-100 p-4 rounded-lg shadow hover:bg-gray-200 transition duration-200 cursor-pointer">

                                <img src="{{ Avatar::create($clockin->name) }}" class="w-10 h-10 rounded-full lazyload"
                                    alt="User Avatar">

                                <div class="text-sm">
                                    <p class="font-semibold">{{ $clockin->name }}</p>
                                    <p class="text-gray-500">
                                        Status:
                                        {{ $clockin->attendances->where('clock_in', '>=', Carbon\Carbon::today())->first()->status ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div x-show="openModal === {{ $index }}" x-cloak
                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
                                @click="openModal = null; modalImage = '';">

                                <!-- Modal Content (Clicking inside shouldn't close the modal) -->
                                <div class="bg-white rounded-lg p-6 max-w-sm w-full relative" @click.stop>

                                    <!-- Close Button -->
                                    <button @click="openModal = null; modalImage = '';"
                                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">
                                        &times;
                                    </button>

                                    <h2 class="text-lg font-bold mb-2">{{ $clockin->name }}</h2>
                                    <p class="text-gray-600">
                                        Status:
                                        {{ $clockin->attendances->where('clock_in', '>=', Carbon\Carbon::today())->first()->status ?? 'N/A' }}
                                    </p>

                                    <label for="clockin">Clock In Photo:</label>
                                    <template x-if="modalImage">
                                        <img :src="modalImage" class="w-auto h-16 rounded-lg"
                                            alt="Clock_in_{{ $clockin->attendances[0]->clock_in }}">
                                    </template>

                                    <p><b>Lokasi:</b>
                                        <a href="https://www.google.com/maps?q={{ $clockin->attendances[0]->clock_in_lat }},{{ $clockin->attendances[0]->clock_in_long }}"
                                            target="_blank" class="text-blue-500 underline">Cek Lokasi</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No users have clocked in today.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>


    <div class="flex flex-col items-center justify-center w-full px-4 pb-8">
        <div class="w-full max-w-md pb-8">
            <div class="w-full shadow-lg mx-auto rounded-xl bg-white px-4 py-4">
                <h1 class="font-bold pb-2 mt-0">Overtime</h1>
                <div
                    class="grid grid-cols-1 gap-4 lg:grid-cols-1 lg:gap-8 max-h-64 overflow-y-auto py-2 px-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200 rounded-md">
                    @for ($dummy = 1; $dummy <= 11; $dummy++)
                        <div class="h-32 rounded-lg bg-gray-200"></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function updateTime() {
            const now = new Date();
            const formattedTime = now.toLocaleString('id-ID', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            document.getElementById('current-time').textContent = formattedTime;
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>
@endpush
