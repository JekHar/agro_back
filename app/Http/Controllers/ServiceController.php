<?php

namespace App\Http\Controllers;

use App\DataTables\ServiceDataTable;
use App\Http\Requests\ServiceRequest;
use App\Models\Merchant;
use App\Models\Service;
use App\Types\MerchantType;
use Illuminate\Http\Request;

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

    public function store(ServiceRequest $request)
    {
        $validated = $request->validated();
        Service::create($validated);

        return redirect()->route('services.index');
    }

    public function edit($id)
    {

        return view('pages.services.form', compact('id'));
    }



    public function update(ServiceRequest $request, Service $service)
    {
        $validated = $request->validated();
        $service->update($validated);

        return redirect()->route('services.index');

    }   

    public function destroy($id)
    {
        Service::destroy($id);

        return redirect()->route('services.index');
    }
}
