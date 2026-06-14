<?php

namespace App\Services;

use App\Models\TeachingAssignment;
use App\Models\TeacherAvailability;
use App\Models\ScheduleEntry;
use App\Models\WeeklySchedule;
use Illuminate\Support\Facades\DB;

class ScheduleGeneratorService
{
    public function generate($weeklyScheduleId)
    {
        $weeklySchedule = WeeklySchedule::findOrFail($weeklyScheduleId);
        
        // 1. Clear existing entries for this week before re-generating
        ScheduleEntry::where('weekly_schedule_id', $weeklySchedule->id)->delete();

        // 2. Load assignments for the semester of this week
        // We assume the week belongs to a semester which has assignments
        $semesterId = $weeklySchedule->academicWeek->semester_id;
        
        $assignments = TeachingAssignment::where('semester_id', $semesterId)
            ->with('teacher', 'classGroup', 'subject')
            ->orderBy('required_hours', 'desc') // Priority to high-hour subjects
            ->get();

        $days = [1, 2, 3, 4, 5]; // Mon-Fri
        $periods = ['P1', 'P2', 'P3', 'P4'];

        DB::transaction(function () use ($assignments, $weeklySchedule, $days, $periods) {
            foreach ($assignments as $assignment) {
                $hoursToSchedule = $assignment->required_hours;
                $scheduledHours = 0;

                // Try to find slots until we meet the required hours
                foreach ($days as $day) {
                    foreach ($periods as $period) {
                        if ($scheduledHours >= $hoursToSchedule) break 2;

                        // CONSTRAINT 1: Is the Teacher available?
                        $isTeacherAvailable = TeacherAvailability::where('teacher_id', $assignment->teacher_id)
                            ->where('day_of_week', $day)
                            ->where('period_id', $period)
                            ->where('available', true)
                            ->exists();
                        
                        if (!$isTeacherAvailable) continue;

                        // CONSTRAINT 2: Is the Teacher already teaching SOMEWHERE ELSE at this time?
                        $isTeacherBusy = ScheduleEntry::where('weekly_schedule_id', $weeklySchedule->id)
                            ->where('day_of_week', $day)
                            ->where('period_id', $period)
                            ->whereHas('teachingAssignment', function($q) use ($assignment) {
                                $q->where('teacher_id', $assignment->teacher_id);
                            })
                            ->exists();
                        
                        if ($isTeacherBusy) continue;

                        // CONSTRAINT 3: Is the Class Group already in another lesson?
                        $isClassBusy = ScheduleEntry::where('weekly_schedule_id', $weeklySchedule->id)
                            ->where('day_of_week', $day)
                            ->where('period_id', $period)
                            ->whereHas('teachingAssignment', function($q) use ($assignment) {
                                $q->where('class_group_id', $assignment->class_group_id);
                            })
                            ->exists();

                        if ($isClassBusy) continue;

                        // ALL CLEAR: Create the entry
                        ScheduleEntry::create([
                            'weekly_schedule_id' => $weeklySchedule->id,
                            'teaching_assignment_id' => $assignment->id,
                            'day_of_week' => $day,
                            'period_id' => $period,
                        ]);

                        $scheduledHours++;
                    }
                }
            }
        });
    }
}
