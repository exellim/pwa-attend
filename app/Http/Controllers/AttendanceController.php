<?php

namespace App\Http\Controllers;

use App\Models\Attendances;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;

class AttendanceController extends Controller
{
    //
    public function clockIn()
    {
        return view('pages.attend.clock_in');
    }

    public function clockOut()
    {
        return view('pages.attend.clock_out');
    }

    public function submitIn(Request $request)
    {
        // Set the directory based on the current date
        $currentDate = Carbon::now()->format('Y-m-d');
        $directory = "absensi/{$currentDate}";

        // Process the uploaded image
        $image = $request->file('foto');
        $filename = Auth::user()->name . '.' . $image->getClientOriginalExtension();

        // Get the original file size in KB
        $originalSize = $image->getSize() / 1024; // Convert to KB (1MB = 1024KB)

        // If the file is already under 1MB, save it directly
        if ($originalSize <= 1024) {
            Storage::disk('public')->putFileAs($directory, $image, $filename);
        } else {
            // Read the image
            $imageData = file_get_contents($image->getRealPath());
            $imageInstance = Image::read($imageData);

            // Start compression
            $quality = 90; // Start with high quality
            do {
                // Select encoder based on image type
                $encoder = match ($image->getClientOriginalExtension()) {
                    'png' => new PngEncoder($quality / 10),  // PNG uses a scale from 0-9
                    default => new JpegEncoder($quality), // JPEG uses percentage (0-100)
                };

                // Encode image with current quality
                $compressedImage = $imageInstance->encode($encoder);

                // Check compressed size
                $compressedSize = strlen($compressedImage) / 1024; // Convert to KB

                // Reduce quality if still above 1MB
                $quality -= 10;
            } while ($compressedSize > 1024 && $quality > 10); // Stop at 10% quality to avoid extreme loss

            // Store the final compressed image
            Storage::disk('public')->put("{$directory}/{$filename}", $compressedImage);
        }

        // Save attendance record
        Attendances::create([
            'user_id' => Auth::user()->id, // Store user_id instead of user
            'status' => $request->status,
            'clock_in' => now(),
            'clock_in_photo' => "{$directory}/{$filename}",
            'clock_in_lat' => $request->latitude,
            'clock_in_long' => $request->longitude,
        ]);

        toast('Absen Berhasil!','success');
        return redirect()->route('home.index');
    }

    public function submitOut(Request $request)
    {
        // Validate request
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:4096', // Max 4MB
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Find today's attendance record for the logged-in user
        $attendance = Attendances::where('user_id', Auth::id())
            ->whereDate('clock_in', Carbon::today())
            ->first();

        // Prevent duplicate clock-outs
        if (!$attendance) {
            return back()->withErrors(['message' => 'You have not clocked in today.']);
        }
        if ($attendance->clock_out) {
            return back()->withErrors(['message' => 'You have already clocked out today.']);
        }

        // Set the directory based on the current date
        $currentDate = Carbon::now()->format('Y-m-d');
        $directory = "absensi/{$currentDate}";

        // Process the uploaded image
        $image = $request->file('foto');
        $filename = Auth::user()->name . '_out.' . $image->getClientOriginalExtension();

        // Get the original file size in KB
        $originalSize = $image->getSize() / 1024; // Convert to KB (1MB = 1024KB)

        // If the file is already under 1MB, save it directly
        if ($originalSize <= 1024) {
            Storage::disk('public')->putFileAs($directory, $image, $filename);
        } else {
            // Read the image
            $imageData = file_get_contents($image->getRealPath());
            $imageInstance = Image::read($imageData);

            // Start compression
            $quality = 90; // Start with high quality
            do {
                // Select encoder based on image type
                $encoder = match ($image->getClientOriginalExtension()) {
                    'png' => new PngEncoder($quality / 10),  // PNG uses a scale from 0-9
                    default => new JpegEncoder($quality), // JPEG uses percentage (0-100)
                };

                // Encode image with current quality
                $compressedImage = $imageInstance->encode($encoder);

                // Check compressed size
                $compressedSize = strlen($compressedImage) / 1024; // Convert to KB

                // Reduce quality if still above 1MB
                $quality -= 10;
            } while ($compressedSize > 1024 && $quality > 10); // Stop at 10% quality to avoid extreme loss

            // Store the final compressed image
            Storage::disk('public')->put("{$directory}/{$filename}", $compressedImage);
        }

        // Update the attendance record with clock-out details
        $attendance->update([
            'clock_out' => now(),
            'clock_out_photo' => "{$directory}/{$filename}",
            'clock_out_lat' => $request->latitude,
            'clock_out_long' => $request->longitude,
        ]);

        toast('Clock Out Successful!', 'success');
        return redirect()->route('home.index');
    }
}
