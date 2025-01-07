<?php

namespace App\Http\Controllers;

use App\DataTables\LotDataTable;
use App\Models\Lot;
use Illuminate\Http\Request;

class LotController extends Controller
{
    public function index(LotDataTable $dataTable)
    {
        return $dataTable->render('pages.lots.index');
    }

    public function create()
    {
        return view('pages.lots.form');
    }

    public function edit($id)
    {
        return view('pages.lots.form', compact('id'));
    }

    public function destroy($id)
    {
        Lot::destroy($id);
        return redirect()->route('lots.index');
    }
}