<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('pages.categories.index');
    }

    public function create()
    {
        return view('pages.categories.form');
    }

    public function edit(string $id)
    {
        return view('pages.categories.form', compact('id'));
    }

    public function destroy(string $id)
    {
        Category::destroy($id);

        return redirect()->route('categories.index');
    }
}
