<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Job;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

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
