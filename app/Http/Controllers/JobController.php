<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


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
}
