<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeachingAssignment;
use App\Models\Subject;

class ReportController extends Controller
{
    public function teacherWorkload()
    {
        $report = Teacher::with(['assignments' => function($query) {
            $query->with('subject');
        }])->get()->map(function ($teacher) {
            $totalRequired = $teacher->assignments->sum('required_hours');
            $totalCompleted = $teacher->assignments->sum('completed_hours');
            
            return [
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'staff_code' => $teacher->staff_code,
                'required_hours' => $totalRequired,
                'completed_hours' => $totalCompleted,
                'progress' => $totalRequired > 0 ? ($totalCompleted / $totalRequired) * 100 : 0
            ];
        });

        return response()->json($report);
    }

    public function subjectProgress()
    {
        $report = Subject::with('assignments')->get()->map(function ($subject) {
            $totalRequired = $subject->assignments->sum('required_hours');
            $totalCompleted = $subject->assignments->sum('completed_hours');
            
            return [
                'subject' => $subject->name,
                'code' => $subject->code,
                'required_hours' => $totalRequired,
                'completed_hours' => $totalCompleted,
                'progress' => $totalRequired > 0 ? ($totalCompleted / $totalRequired) * 100 : 0
            ];
        });

        return response()->json($report);
    }
}

