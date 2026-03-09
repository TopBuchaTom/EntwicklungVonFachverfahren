<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    public function index(Request $request)
    {
        $query = Travel::query();

        return view('travels.index', [
            'travels' => $query->paginate(10),
        ]);
    }

    public function create()
    {
        return view('travels.create');
    }

    public function store(Request $request, Travel $travel)
    {
        $travel = $travel->create($request->all());

        return redirect()->route('travels.show', ['travel' => $travel]);
    }

    public function show(Travel $travel)
    {
        return view('travels.show', ['travel' => $travel]);
    }

    public function edit(Travel $travel)
    {
        return view('travels.edit', ['travel' => $travel]);
    }

    public function update(Request $request, Travel $travel) {
        $travel->update($request->all());

        return redirect()->route('travels.show', ['travel' => $travel]);
    }

    public function destroy(Travel $travel)
    {
        $travel->delete();

        return redirect()->route('travels.index');
    }
}
