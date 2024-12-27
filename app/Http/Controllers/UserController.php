<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    // Mostrar lista de usuarios
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('pages.users.index');
    }

    public function create()
    {
        return view('pages.users.form');
    }

    // Guardar nuevo usuario
    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        User::create($validated);

        return redirect()->route('users.index');
    }

    // public function show(User $user)
    // {
    //     return view('users.show', compact('user'));
    // }

    public function edit($id)
    {
        return view('pages.users.form', compact('id'));
    }

    // Actualizar usuario
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();
        $user->update($validated);

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        User::destroy($id);

        return redirect()->route('users.index');
    }
}
