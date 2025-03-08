@extends('layouts.app')

@section('content')
    <!-- Root Element for Alpine.js State -->
    <div class="flex flex-col items-center justify-center w-full px-4" x-data="{ openCreateTaskModal: false, openTaskModal: null, modalImage: '', modalTaskName: '' }">

        <div class="m-4 w-full max-w-md">
            <div class="credit-card w-full shadow-lg mx-auto rounded-xl bg-white">
                <header class="flex flex-col justify-center items-center px-5 py-5">
                    <div
                        class="relative w-full h-56 bg-gradient-to-r from-blue-700 to-red-500 text-white rounded-xl shadow-lg px-6">
                        <!-- Company Logo -->
                        <div class="absolute top-5 right-5">
                            <img src="{{ asset('images/logos/only logo.png') }}" class="w-[60px]" alt="Company Logo">
                        </div>

                        <!-- User Information -->
                        <div class="absolute top-14 left-5 flex items-center space-x-3">
                            <img src="{{ Avatar::create(Auth::user()->name) }}" class="w-[60px] h-[60px] rounded-full"
                                alt="User Avatar">
                            <div class="flex flex-col text-white">
                                <b class="text-lg">{{ Auth::user()->name }}</b>
                                <b class="text-sm text-gray-300">{{ Auth::user()->nip }}</b>
                            </div>
                        </div>

                        <!-- Live Updating Time -->
                        <div class="absolute bottom-10 left-6 text-lg tracking-widest font-mono">
                            <span id="current-time"></span>
                        </div>

                        <!-- Task Counter -->
                        <div class="absolute bottom-5 left-6 text-sm font-semibold uppercase">
                            Total Task Bulan ini:    {{ $tasks->whereBetween('task_date', [now()->startOfMonth()->format('Y-m-d'), now()->endOfMonth()->format('Y-m-d')])->count() }}
                        </div>
                    </div>
                </header>
            </div>
        </div>

        <!-- Floating Button -->
        <button @click="openCreateTaskModal = true"
            class="fixed bottom-30 right-6 bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition">
            +
        </button>

        <!-- Task Creation Modal -->
        <div x-show="openCreateTaskModal" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
            @click="openCreateTaskModal = false">
            <div class="bg-white p-6 rounded-lg w-80 shadow-lg relative" @click.stop>
                <button @click="openCreateTaskModal = false"
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">
                    &times;
                </button>
                <h2 class="text-lg font-bold mb-4">Create New Task</h2>
                <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="task_name" placeholder="Task Name" class="w-full p-2 border rounded mb-2"
                        required>

                    <!-- Date Picker (Disabled Dates for Non-Clocked-In Days) -->
                    <input type="date" name="task_date" id="task_date" class="w-full p-2 border rounded mb-2" required>

                    <input type="file" name="task_photo" class="w-full p-2 border rounded mb-4" accept="image/*"
                        required>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                        Submit
                    </button>
                </form>
            </div>
        </div>

        <!-- Scrollable Task List -->
        <div class="flex flex-col items-center justify-center w-full px-4 py-8">
            <div class="w-full max-w-md pb-2">
                <div class="w-full shadow-lg mx-auto rounded-xl bg-white px-4 py-4">
                    <h1 class="font-bold pb-2 mt-0">Task / Tanggal</h1>
                    <div
                        class="grid grid-cols-1 gap-4 max-h-140 overflow-y-auto py-2 px-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200 rounded-md">
                        @forelse ($tasks->groupBy('task_date') as $date => $taskGroup)
                            <div class="pt-4 pb-4">
                                <p class="text-gray-700 font-semibold mb-3">
                                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
                                @foreach ($taskGroup as $t)
                                    <div @click="openTaskModal = {{ $t->id }}; modalImage = '{{ asset('storage/' . $t->task_photo) }}'; modalTaskName = '{{ $t->task_name }}';"
                                        class="h-32 flex items-center justify-center rounded-lg bg-gray-200 p-4 shadow hover:bg-gray-300 transition cursor-pointer mb-4">
                                        <p class="text-gray-700 font-semibold">{{ $t->task_name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <p class="text-gray-500 py-4">No tasks available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


        <!-- Task Details Modal -->
        <div x-show="openTaskModal !== null" x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
            @click="openTaskModal = null; modalImage = ''; modalTaskName = '';" x-data="{ isImageEnlarged: false }">

            <div class="bg-white rounded-lg p-6 max-w-sm w-full relative" @click.stop>
                <!-- Close Button -->
                <button @click="openTaskModal = null; modalImage = ''; modalTaskName = ''; isImageEnlarged = false;"
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">
                    &times;
                </button>

                <h2 class="text-lg font-bold mb-2" x-text="modalTaskName"></h2>

                <label for="taskPhoto">Task Photo:</label>

                <!-- Image with Click-to-Enlarge Feature -->
                <template x-if="modalImage">
                    <img :src="modalImage"
                        class="rounded-lg object-cover mt-2 shadow transition-all duration-300 cursor-pointer"
                        :class="isImageEnlarged ? 'w-full h-auto max-w-2xl max-h-screen' : 'h-40 w-full'"
                        @click="isImageEnlarged = !isImageEnlarged">
                </template>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch clocked-in dates from the backend
            let validDates = @json($clockedInDates); // This will be an array of valid dates

            // Select the date input field
            let dateInput = document.getElementById('task_date');

            // Disable invalid dates
            dateInput.addEventListener('input', function() {
                let selectedDate = this.value;
                if (!validDates.includes(selectedDate)) {
                    alert("You can only add tasks on days you have clocked in.");
                    this.value = ''; // Clear invalid selection
                }
            });

            // Set min/max date dynamically (optional)
            let today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute("max", today); // Restrict future dates
        });
    </script>
@endpush
