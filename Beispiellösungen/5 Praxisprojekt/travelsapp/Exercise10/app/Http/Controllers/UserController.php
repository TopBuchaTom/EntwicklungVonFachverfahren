<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function __construct()
    {
        // Empfohlen, da aspektorientiert, weil in keiner Action mehr Autorisierung geprüft werden muss,
        // da über folgenden Aufruf automatisch zwischen Controller-Methoden und Policy-Methoden via Namen gematched wird
        $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        return view('users.index', [
            'users' => User::all()
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request, User $user) {
        $user = $user->create($request->all() + ['is_admin' => 0, 'is_verifier' => 0, 'is_approver' => 0]);

        return redirect()->route('users.show', ['user' => $user]);
    }

    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user) {
        $user->update($request->all() + ['is_admin' => 0, 'is_verifier' => 0, 'is_approver' => 0]);

        return redirect()->route('users.show', ['user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
