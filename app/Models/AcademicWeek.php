<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicWeek extends Model
{
    protected $fillable = ['semester_id', 'week_number', 'start_date', 'end_date'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
