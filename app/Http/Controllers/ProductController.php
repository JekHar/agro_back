<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('pages.products.index');
    }

    public function create()
    {
        return view('pages.products.form');
    }

    public function edit($id)
    {
        return view('pages.products.form', compact('id'));
    }

    public function destroy($id)
    {
        Product::destroy($id);

        return redirect()->route('products.index');
    }
}
