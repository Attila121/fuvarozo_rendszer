<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Job;

class UserController extends Controller
{
    // Fetches jobs for a specific driver
    public function driverJobs($driverId)
    {
        $user = auth()->user();

        // Only admin or the driver themselves can see their jobs
        if (!$user->isAdmin() && $user->id != $driverId) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $jobs = Job::where('driver_id', $driverId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'jobs' => $jobs
        ]);
    }

    // Fetches all drivers
    public function drivers()
    {
        try {
            if (!auth()->user()->isAdmin()) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            $drivers = User::where('role', 'driver')
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();

            return response()->json([
                'status' => 'success',
                'drivers' => $drivers
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching drivers:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch drivers'
            ], 500);
        }
    }

    // Fetches all admins
    public function admins()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $admins = User::where('role', 'admin')
            ->select('id', 'name')
            ->get();

        return response()->json([
            'admins' => $admins
        ]);
    }
}
