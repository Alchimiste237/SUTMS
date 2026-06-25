<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleEntry extends Model
{
    protected $fillable = [
        'weekly_schedule_id', 
        'teaching_assignment_id', 
        'teacher_id', 
        'is_personal_working_hour', 
        'class_group_id', 
        'day_of_week', 
        'period_id'
    ];

    public function weeklySchedule()
    {
        return $this->belongsTo(WeeklySchedule::class);
    }

    public function teachingAssignment()
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }
}
