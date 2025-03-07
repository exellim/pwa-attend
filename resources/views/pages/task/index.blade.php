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
                            <img src="{{ Avatar::create(Auth::user()->name) }}" class="w-[60px] h-[60px] rounded-full"
                                alt="User Avatar">

                            <!-- Text Content (Aligned Right) -->
                            <div class="flex flex-col text-white">
                                <b class="text-lg">{{ Auth::user()->name }}</b>
                                <b class="text-sm text-gray-300">{{ Auth::user()->nip }}</b>
                            </div>
                        </div>

                        <!-- Live Updating Time -->
                        <div class="absolute bottom-10 left-6 text-lg tracking-widest font-mono">
                            <span id="current-time"></span>
                        </div>

                        <!-- Cardholder Name -->
                        <div class="absolute bottom-5 left-6 text-sm font-semibold uppercase">
                            Total Task Bulan ini: -
                        </div>
                    </div>
                </header>
            </div>
        </div>
    </div>

    <!-- Scrollable Sections -->
    <div class="flex flex-col items-center justify-center w-full px-4 py-8">
        <div class="w-full max-w-md pb-2">
            <div class="w-full shadow-lg mx-auto rounded-xl bg-white px-4 py-4">
                <h1 class="font-bold pb-2 mt-0">Task /Tanggal</h1>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-4 lg:gap-8 max-h-140 overflow-y-auto py-2 px-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200 rounded-md">
                    @for ($dummy = 1; $dummy <= 11; $dummy++)
                        <div class="h-32 rounded-lg bg-gray-200"></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- JavaScript to Update Time Every Second -->
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
