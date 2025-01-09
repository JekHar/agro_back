<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Models\User;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('pages.users.index');
    }

    public function create()
    {
        return view('pages.users.form');
    }

    public function edit($id)
    {
        return view('pages.users.form', compact('id'));
    }

    public function destroy($id)
    {
        User::destroy($id);

        return redirect()->route('users.index');
    }
}
