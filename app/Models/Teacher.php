<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['user_id', 'staff_code', 'first_name', 'last_name', 'phone', 'teacher_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(TeacherAvailability::class);
    }

    public function assignments()
    {
        return $this->hasMany(TeachingAssignment::class);
    }
}
