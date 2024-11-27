<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Notifications\JobFailedNotification;

class JobController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index()
    {
        try {
            // Check if the user is authenticated
            if (!auth()->user()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Log the user accessing jobs
            Log::info('User accessing jobs', [
                'user_id' => auth()->id(),
                'is_admin' => auth()->user()->isAdmin()
            ]);

            // Fetch jobs based on user role
            if (auth()->user()->isAdmin()) {
                $jobs = Job::with('driver')->get();
            } else {
                $jobs = Job::where('driver_id', auth()->id())->get();
            }

            // Return the jobs in JSON format
            return response()->json([
                'status' => 'success',
                'jobs' => $jobs
            ]);
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Error in jobs index:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch jobs'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'pickup_address' => 'required|string',
            'delivery_address' => 'required|string',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
            'driver_id' => 'required|exists:users,id'
        ]);

        // Create a new job with the validated data
        $job = Job::create([...$validated, 'status' => Job::STATUS_ASSIGNED]);

        // Return a success response
        return response()->json([
            'message' => 'Job created',
            'job' => $job
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $user = Auth::user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // If the user is an admin, validate and update the job
        if ($user->isAdmin()) {
            $validated = $request->validate([
                'pickup_address' => 'sometimes|required|string',
                'delivery_address' => 'sometimes|required|string',
                'recipient_name' => 'sometimes|required|string',
                'recipient_phone' => 'sometimes|required|string',
                'driver_id' => 'sometimes|required|exists:users,id',
                'status' => 'sometimes|in:' . implode(',', [
                    Job::STATUS_ASSIGNED,
                    Job::STATUS_IN_PROGRESS,
                    Job::STATUS_COMPLETED,
                    Job::STATUS_FAILED
                ])
            ]);

            // Notify admins if the job status is updated to failed
            if (isset($validated['status']) && $validated['status'] === Job::STATUS_FAILED) {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new JobFailedNotification($job));
                }
            }

            // Log the job update
            Log::info('Job updated by admin', [
                'job_id' => $job->id,
                'admin_id' => $user->id,
                'changes' => $validated
            ]);

            // Update the job with the validated data
            $job->update($validated);

            // Return a success response
            return response()->json([
                'message' => 'Job updated successfully',
                'job' => $job->fresh()
            ]);
        }

        // If the user is not an admin, they can only update the job status
        if ($job->driver_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized to update this job'
            ], 403);
        }

        // Validate the status update
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', [
                Job::STATUS_IN_PROGRESS,
                Job::STATUS_COMPLETED,
                Job::STATUS_FAILED
            ])
        ]);

        // Update the job status
        $job->update($validated);

        // Return a success response
        return response()->json([
            'message' => 'Job status updated',
            'job' => $job->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        // Check if the user is an admin
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Delete the job
        $job->delete();

        // Return a success response
        return response()->json([
            'message' => 'Job deleted'
        ]);
    }

    /**
     * Assign a job to a driver.
     */
    public function assignDriver(Request $request, Job $job)
    {
        // Check if the user is an admin
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        // Validate the request data
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id'
        ]);

        // Update the job with the driver ID and set the status to assigned
        $job->update([
            'driver_id' => $validated['driver_id'],
            'status' => Job::STATUS_ASSIGNED
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Driver assigned successfully',
            'job' => $job
        ]);
    }
}
