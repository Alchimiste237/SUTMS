<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['academic_year_id', 'name', 'start_date', 'end_date', 'is_active'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function weeks()
    {
        return $this->hasMany(AcademicWeek::class);
    }
}
