<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $drivers = User::where('role', 'driver')
            ->select('id', 'name')
            ->get();

        return response()->json([
            'drivers' => $drivers
        ]);
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
