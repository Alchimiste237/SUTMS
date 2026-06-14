<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherAvailabilityRequest;
use App\Models\TeacherAvailability;

class TeacherAvailabilityController extends Controller
{
    public function store(StoreTeacherAvailabilityRequest $request)
    {
        TeacherAvailability::updateOrCreate(
            [
                'teacher_id' => $request->teacher_id,
                'day_of_week' => $request->day_of_week,
                'period_id' => $request->period_id,
            ],
            ['available' => $request->available]
        );

        return redirect()->back()->with('success', 'Availability updated successfully.');
    }
}
