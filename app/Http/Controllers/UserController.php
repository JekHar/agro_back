<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Mostrar lista de usuarios
    public function index(UserDataTable $dataTable): mixed
    {
        return $dataTable->render('pages.users.index');
    }

    public function create(): View
    {
        return view('pages.users.create');
    }

    // Guardar nuevo usuario
    public function store(UserRequest $request): RedirectResponse
    {

        $referer = $request->headers->get('referer');
        $usersUrl = route('users.index');

        User::create($request->validated());

        if ($referer != $usersUrl) {
            return redirect()->to($referer)->with('success', 'User created successfully.');
        }

        return redirect()->route('users.index');
    }

    // public function show(User $user)
    // {
    //     return view('users.show', compact('user'));
    // }

    public function edit(User $user): View
    {
        return view('pages.users.edit', compact('user'));
    }

    // Actualizar usuario
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()->route('users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
