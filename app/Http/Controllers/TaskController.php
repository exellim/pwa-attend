<?php

namespace App\Http\Controllers;

use App\Models\Attendances;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch all tasks for the authenticated user
        $tasks = Task::where('user_id', $user->id)
                    ->orderBy('task_date', 'desc')
                    ->get();

        // Fetch all valid clock-in dates (days the user has clocked in)
        $clockedInDates = Attendances::where('user_id', $user->id)
                                    ->pluck('clock_in')
                                    ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
                                    ->toArray(); // Convert collection to array for Blade

        return view('pages.task.index', compact('tasks', 'clockedInDates'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'task_date' => 'required|date',
            'task_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Limit to 5MB
        ]);

        $user = Auth::user();
        $taskDate = $request->task_date;

        // Ensure the user has clocked in on the selected date
        $hasClockedIn = Attendances::where('user_id', $user->id)
                                  ->whereDate('clock_in', $taskDate)
                                  ->exists();

        if (!$hasClockedIn) {
            return back()->withErrors(['task_date' => 'You can only add tasks on days you have clocked in.']);
        }

        // Handle Image Upload and Compression
        $taskPhotoPath = null;
        if ($request->hasFile('task_photo')) {
            $image = $request->file('task_photo');

            // Directory: task/{task_date}/{username}/
            $directory = "task/{$taskDate}/{$user->name}";

            // File Name: task_name.extension
            $filename = $request->task_name . '.' . $image->getClientOriginalExtension();

            // Get original file size in KB
            $originalSize = $image->getSize() / 1024; // Convert bytes to KB

            if ($originalSize <= 1024) {
                // If under 1MB, store directly
                Storage::disk('public')->putFileAs($directory, $image, $filename);
            } else {
                // Read the image
                $imageData = file_get_contents($image->getRealPath());
                $imageInstance = Image::read($imageData);

                // Start compression
                $quality = 90;
                do {
                    // Select encoder based on image type
                    $encoder = match ($image->getClientOriginalExtension()) {
                        'png' => new PngEncoder($quality / 10),  // PNG uses scale 0-9
                        default => new JpegEncoder($quality), // JPEG uses percentage 0-100
                    };

                    // Encode image with current quality
                    $compressedImage = $imageInstance->encode($encoder);

                    // Check compressed size
                    $compressedSize = strlen($compressedImage) / 1024; // Convert to KB

                    // Reduce quality if still above 1MB
                    $quality -= 10;
                } while ($compressedSize > 1024 && $quality > 10); // Stop at 10% quality

                // Store the final compressed image
                Storage::disk('public')->put("{$directory}/{$filename}", $compressedImage);
            }

            // Save relative path to database
            $taskPhotoPath = "{$directory}/{$filename}";
        }

        // Create Task Entry
        Task::create([
            'user_id' => $user->id,
            'task_name' => $request->task_name,
            'task_date' => $taskDate,
            'task_photo' => $taskPhotoPath, // Store relative path
        ]);

        return back()->with('success', 'Task added successfully.');
    }
}
