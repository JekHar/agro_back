<?php

namespace App\Http\Controllers;

use App\DataTables\MerchantsDataTable;
use App\Http\Requests\MerchantRequest;
use App\Models\Merchant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MerchantsDataTable $dataTable): mixed
    {
        return $dataTable->render('pages.merchants.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.merchants.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MerchantRequest $request): RedirectResponse
    {
        $merchantData = $request->validated();

        $merchantData['merchant_type'] = $this->isClientRoute($request) ? 'Client' : 'Tenant';

        Merchant::create($merchantData);

        return redirect()->route($this->getRedirectRoute($request) . '.index')->with('success', 'Merchant created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Merchant $merchant): View
    {
        return view('pages.merchants.form', compact('merchant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MerchantRequest $request, Merchant $merchant): RedirectResponse
    {
        $merchantData = $request->validated();

        $merchant->update($merchantData);

        return redirect()->route($this->getRedirectRoute($request) . '.index')->with('success', 'Merchant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Merchant $merchant): RedirectResponse
    {
        $merchant->delete();

        return redirect()->route($this->getRedirectRoute(request()) . '.index')->with('success', 'Merchant deleted successfully.');
    }

    /**
     * Determine if the current route is for Client.
     */
    private function isClientRoute(Request $request): bool
    {
        return $request->routeIs('merchants.clients.*');
    }

    /**
     * Get the redirect route base name based on the request.
     */
    private function getRedirectRoute(Request $request): string
    {
        return $this->isClientRoute($request) ? 'merchants.clients' : 'merchants.tenants';
    }
}
