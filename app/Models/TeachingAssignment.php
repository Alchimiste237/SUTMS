<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingAssignment extends Model
{
    protected $fillable = ['teacher_id', 'subject_id', 'class_group_id', 'semester_id', 'required_hours', 'completed_hours', 'remaining_hours'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
