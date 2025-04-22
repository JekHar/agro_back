<?php

namespace App\Http\Controllers;

use App\DataTables\LotDataTable;
use App\Models\Lot;
use App\Models\Merchant;

class LotController extends Controller
{
    public function index(LotDataTable $dataTable, $merchant_id = null)
    {
        $merchant = null;
        
        if ($merchant_id) {
            session(['merchant_id' => $merchant_id]);
            
            $merchant = Merchant::where('id', $merchant_id)
                ->where('merchant_type', 'Client')
                ->first();
            
            if (!$merchant) {
                return redirect()->route('clients.merchants.index')
                    ->with('error', 'Cliente no encontrado');
            }
            
            $viewData = [
                'merchant' => $merchant,
                'showMerchantContext' => true
            ];
            
            return $dataTable->render('pages.lots.index', $viewData);
        }
        
        session()->forget('merchant_id');
        
        return $dataTable->render('pages.lots.index', ['showMerchantContext' => false]);
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
