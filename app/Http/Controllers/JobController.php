<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



/*

Adminisztrátor: Képes munkákat létrehozni, módosítani, törölni és fuvarozókhoz
rendelni.
Fuvarozó: Megtekintheti a neki kiosztott munkákat, és frissítheti azok státuszát. 


Adminisztrátor funkciói:
1. Munkák létrehozása: Az adminisztrátor létrehozhat új fuvarfeladatokat, melyek
tartalmazzák a kiindulási címet, érkezési címet, címzett nevét és elérhetőségét.
2. Munkák módosítása: Munkák adatai (pl. címek, címzett) módosíthatók az
adminisztrátor által.
3. Munkák törlése: Adminisztrátor törölhet munkákat a rendszerből.
4. Munkák fuvarozókhoz rendelése: Az adminisztrátor a létrehozott munkákat
fuvarozókhoz rendelheti. 

Fuvarozó funkciói:
1. Munkák megtekintése: Fuvarozók megtekinthetik a nekik kiosztott munkákat, azok
státuszát, valamint a címzett adatait.
2. Munkák státuszának módosítása: A fuvarozó a neki kiosztott munka státuszát
tudja frissíteni:
◦ Kiosztva
◦ Folyamatban
◦ Elvégezve
◦ Sikertelen (pl. a címzett nem volt elérhető)


*/

class JobController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        

        if ($user->isAdmin()) {
            $jobs = Job::with('driver')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'pickup_address' => $job->pickup_address,
                    'delivery_address' => $job->delivery_address,
                    'recipient_name' => $job->recipient_name,
                    'recipient_phone' => $job->recipient_phone,
                    'status' => $job->status,
                    'driver' => $job->driver ? [
                        'id' => $job->driver->id,
                        'name' => $job->driver->name,
                    ] : null,
                    'created_at' => $job->created_at->format('Y-m-d H:i:s')
                ];
            });
        } else {
            $jobs = $user->jobs()
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($job) {
                        return [
                            'id' => $job->id,
                            'pickup_address' => $job->pickup_address,
                            'delivery_address' => $job->delivery_address,
                            'recipient_name' => $job->recipient_name,
                            'recipient_phone' => $job->recipient_phone,
                            'status' => $job->status,
                            'created_at' => $job->created_at->format('Y-m-d H:i:s')
                        ];
                    });
        }

        return response()->json([
            'success' => true,
            'jobs' => $jobs
        ]);
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
        $validated = $request->validate([
            'pickup_address' => 'required|string',
            'delivery_address' => 'required|string',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
            'driver_id' => 'required|exists:users,id'
        ]);

        $job = Job::create([...$validated, 'status' => Job::STATUS_ASSIGNED]);

        return response()->json([
            'message' => 'Job created',
            'job' => $job
        ], 201);


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
    public function update(Request $request, Job $job)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        } 

        if ($user->isAdmin()){
            $validated = $request->validate([
                'pickup_address' => 'required|string',
                'delivery_address' => 'required|string',
                'recipient_name' => 'required|string',
                'recipient_phone' => 'required|string',
                'driver_id' => 'required|exists:users,id',
                'status' => 'sometimes|in,' . implode(',', [
                    Job::STATUS_ASSIGNED,
                    Job::STATUS_IN_PROGRESS,
                    Job::STATUS_COMPLETED,
                    Job::STATUS_FAILED
                ])
            ]);
        }else{
            $validated = $request->validate([
                'status' => 'required|in,' . implode(',', [
                    Job::STATUS_IN_PROGRESS,
                    Job::STATUS_COMPLETED,
                    Job::STATUS_FAILED
                ])
            ]);
        }

        $job->update($validated);

        return response()->json([
            'message' => 'Job updated',
            'job' => $job
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {

        if(!Auth::user()->isAdmin()){
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $job->delete();

        return response()->json([
            'message' => 'Job deleted'
        ]);
    }

    /**
     * Assign a job to a driver.
     */
    public function assignDriver(Request $request, Job $job){
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id'
        ]);

        $job->update([
            'driver_id' => $validated['driver_id'],
            'status' => Job::STATUS_ASSIGNED
        ]);

        return response()->json([
            'message' => 'Driver assigned successfully',
            'job' => $job
        ]);
    }
}
