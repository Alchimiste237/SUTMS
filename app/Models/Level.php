<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name'];

    public function classGroups()
    {
        return $this->hasMany(ClassGroup::class);
    }
}
