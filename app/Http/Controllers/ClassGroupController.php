<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassGroupRequest;
use App\Models\ClassGroup;

class ClassGroupController extends Controller
{
    public function store(StoreClassGroupRequest $request)
    {
        ClassGroup::create($request->validated());

        return redirect()->back()->with('success', 'Class Group created successfully.');
    }
}
