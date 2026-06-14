<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLevelRequest;
use App\Models\Level;

class LevelController extends Controller
{
    public function store(StoreLevelRequest $request)
    {
        Level::create($request->validated());

        return redirect()->back()->with('success', 'Level created successfully.');
    }
}
