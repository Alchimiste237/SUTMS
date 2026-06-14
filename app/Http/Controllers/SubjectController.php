<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function store(StoreSubjectRequest $request)
    {
        Subject::create($request->validated());

        return redirect()->back()->with('success', 'Subject created successfully.');
    }
}
