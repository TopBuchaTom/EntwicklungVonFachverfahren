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

    public function index(Request $request)
    {
        $query = User::query();

        if (isset($request->filter_value))
            $query = $query->where($request->filter_criteria, 'like',  "%$request->filter_value%");
        if (isset($request->filter_isadmin))
            $query = $query->where('is_admin', $request->filter_isadmin);
        if (isset($request->filter_isverifier))
            $query = $query->where('is_verifier', $request->filter_isverifier);
        if (isset($request->filter_isapprover))
            $query = $query->where('is_approver', $request->filter_isapprover);

        return view('users.index', [
            'users' => $query->paginate(10),
            'filter_criteria' => $request->filter_criteria,
            'filter_value' => $request->filter_value,
            'filter_isadmin' => $request->filter_isadmin,
            'filter_isverifier' => $request->filter_isverifier,
            'filter_isapprover' => $request->filter_isapprover
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
