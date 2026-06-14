<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklySchedule extends Model
{
    protected $fillable = ['academic_week_id', 'status'];

    public function academicWeek()
    {
        return $this->belongsTo(AcademicWeek::class);
    }

    public function entries()
    {
        return $this->hasMany(ScheduleEntry::class);
    }
}
