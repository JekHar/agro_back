<?php

namespace App\Http\Controllers;

use App\DataTables\ServiceDataTable;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(ServiceDataTable $dataTable)
    {
        return $dataTable->render('pages.services.index');
    }

    public function create()
    {
        return view('pages.services.form');
    }

    public function edit($id)
    {
        return view('pages.services.form', compact('id'));
    }

    public function destroy($id)
    {
        Service::destroy($id);

        return redirect()->route('services.index');
    }
}
