<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Models\Teacher;

class TeacherController extends Controller
{
    public function store(StoreTeacherRequest $request)
    {
        Teacher::create($request->validated());

        return redirect()->back()->with('success', 'Teacher created successfully.');
    }
}
