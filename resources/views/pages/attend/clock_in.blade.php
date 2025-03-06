@extends('layouts.app')

@push('style')
    <!-- Select2 Styles -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Remove Select2 border and background */
        .select2-container--default .select2-selection--single {
            border: none !important;
            background: transparent !important;
            outline: none !important;
            box-shadow: none !important;
        }

        /* Adjust Select2 placeholder position */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #9CA3AF !important;
            line-height: 1.5rem !important;
            padding-left: 8px !important;
        }

        /* Align Select2 dropdown arrow */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%);
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col items-center justify-center px-4 bg-gray-100 min-h-screen">
        <div class="w-full max-w-md">
            <div class="shadow-lg rounded-xl bg-white p-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 text-center">Lokasi</h2>

                <form action="{{ route('attend.submitIn') }}" method="POST" enctype= multipart/form-data class="px-2">
                    @csrf
                    <!-- Latitude Input (Read-Only) -->
                    <div class="py-2">
                        <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                        <div
                            class="flex items-center border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500">
                            <input type="text" name="latitude" id="latitude" readonly
                                class="w-full bg-transparent outline-none text-gray-700 py-2 placeholder-gray-400"
                                placeholder="Fetching location...">
                        </div>
                    </div>

                    <!-- Longitude Input (Read-Only) -->
                    <div class="py-2">
                        <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                        <div
                            class="flex items-center border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500">
                            <input type="text" name="longitude" id="longitude" readonly
                                class="w-full bg-transparent outline-none text-gray-700 py-2 placeholder-gray-400"
                                placeholder="Fetching location...">
                        </div>
                    </div>

                    <!-- Status Select2 Dropdown -->
                    <div class="py-2">
                        <label for="example-select" class="block text-sm font-medium text-gray-700">Status</label>
                        <div
                            class="border border-gray-300 rounded-lg bg-gray-50 px-4 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                            <select id="example-select" name="status"
                                class="select2 w-full bg-transparent outline-none border-none focus:ring-0">
                                <option value="">Choose an option</option>
                                <option value="Masuk On Time">Masuk On Time</option>
                                <option value="Masuk Terlambat">Masuk Terlambat</option>
                                <option value="Visit" class="hidden">Visit</option>
                                <option value="Site" class="hidden">Site</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Sakit">Sakit</option>
                            </select>
                        </div>
                    </div>

                    <!-- Foto (Camera Upload Only) -->
                    <h2 class="text-lg font-semibold text-gray-800 mb-2 pt-4 text-center">Foto</h2>
                    <div class="py-2">
                        <label for="foto" class="block text-sm font-medium text-gray-700">Hasil</label>
                        <div
                            class="flex items-center border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500">
                            <input type="file" name="foto" id="foto" accept="image/*" capture="environment"
                                class="w-full bg-transparent outline-none text-gray-700 py-2 placeholder-gray-400">
                        </div>
                    </div>

                    <!-- Image Preview Box -->
                    <div class="py-4 flex justify-center">
                        <img id="image-preview"
                            class="hidden w-40 h-40 object-cover border border-gray-300 rounded-lg shadow-md" />
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-300">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#example-select').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%'
            });

            // Target location for Visit & Site
            const targetLat = 8.635829;
            const targetLng = 115.221045;
            const allowedRadius = 100; // in meters

            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371e3; // Earth’s radius in meters
                const toRad = (value) => value * Math.PI / 180;
                const dLat = toRad(lat2 - lat1);
                const dLon = toRad(lon2 - lon1);
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c; // Distance in meters
            }

            function updateLocation(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                $('#latitude').val(lat.toFixed(6));
                $('#longitude').val(lon.toFixed(6));

                const distance = calculateDistance(lat, lon, targetLat, targetLng);
                let $select = $('#example-select');

                // Remove existing Visit & Site options before re-adding them
                $select.find('option[value="Visit"], option[value="Site"]').remove();

                if (distance <= allowedRadius) {
                    // If within 100m, add options back
                    $select.append('<option value="Visit">Visit</option>');
                    $select.append('<option value="Site">Site</option>');
                }

                // **Force Select2 to refresh and apply changes**
                $select.trigger('change');
            }


            function handleError(error) {
                let errorMessage = "Unknown error occurred while fetching location.";

                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = "Location permission denied. Please enable it in your browser settings.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = "GPS signal weak or unavailable. Try moving to an open area.";
                        break;
                    case error.TIMEOUT:
                        errorMessage = "Location request timed out. Retrying...";
                        break;
                    case error.UNKNOWN_ERROR:
                        errorMessage = "An unknown error occurred.";
                        break;
                }

                console.error("Error getting location: ", errorMessage);
                $('#latitude').val(errorMessage);
                $('#longitude').val(errorMessage);

                // Retry fetching location if it's a temporary issue (POSITION_UNAVAILABLE or TIMEOUT)
                if (error.code === error.POSITION_UNAVAILABLE || error.code === error.TIMEOUT) {
                    setTimeout(() => {
                        console.log("Retrying location request...");
                        navigator.geolocation.getCurrentPosition(updateLocation, handleError, {
                            enableHighAccuracy: true,
                            timeout: 5000,
                            maximumAge: 0
                        });
                    }, 5000); // Retry after 5 seconds
                }
            }


            if ("geolocation" in navigator) {
                navigator.geolocation.watchPosition(updateLocation, handleError, {
                    enableHighAccuracy: true,
                    maximumAge: 5000
                });
            } else {
                $('#latitude').val("Geolocation not supported");
                $('#longitude').val("Geolocation not supported");
            }

            // Image preview functionality
            $('#foto').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result).removeClass('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#image-preview').addClass('hidden');
                }
            });

        });
    </script>
@endpush
