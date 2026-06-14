<?php

namespace Database\Seeders;

use App\Models\WeeklySchedule;
use App\Models\AcademicWeek;
use Illuminate\Database\Seeder;

class WeeklyScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Assuming at least one academic week exists
        $week = AcademicWeek::first();
        if ($week) {
            WeeklySchedule::create(['academic_week_id' => $week->id, 'status' => 'DRAFT']);
        }
    }
}
