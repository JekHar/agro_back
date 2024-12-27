<?php

namespace App\Http\Controllers;

use App\DataTables\AircraftDataTable;
use App\Models\Aircraft;
use Illuminate\Http\Request;

class AircraftController extends Controller
{
    public function index(AircraftDataTable $dataTable)
    {
        return $dataTable->render('pages.aircraft.index');
    }

    public function create()
    {
        return view('pages.aircraft.form');
    }

    public function edit($id)
    {
       
        return view('pages.aircraft.form', compact('id'));
    }
}
