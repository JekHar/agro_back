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
        $merchants = Merchant::where('merchant_type', MerchantType::CLIENT)->pluck('business_name', 'id');   
        return view('pages.services.create', compact('merchants'));
    }

    public function store(ServiceRequest $request)
    {
        $validated = $request->validated();
        Service::created($validated);
        return redirect()->route('services.index');
    }

    public function show($id)
    {
        return view('services.show');
    }

    public function edit($id)
    {
        return view('services.edit');
    }

    public function update(Request $request, $id)
    {
        //
    }   

    public function destroy($id)
    {
        //
    }
}
