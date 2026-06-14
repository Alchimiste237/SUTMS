<?php

namespace App\Livewire;

use App\Models\Teacher;
use App\Models\TeacherAvailability;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TeacherAvailabilityManager extends Component
{
    public $teacher;
    public $days = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri'];
    public $periods = ['P1', 'P2', 'P3', 'P4'];
    public $availability = [];

    public function mount()
    {
        // Assuming current user is a teacher
        $this->teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        $this->loadAvailability();
    }

    public function loadAvailability()
    {
        $data = TeacherAvailability::where('teacher_id', $this->teacher->id)->get();
        foreach ($data as $item) {
            $this->availability[$item->day_of_week][$item->period_id] = (bool)$item->available;
        }
    }

    public function toggleAvailability($day, $period)
    {
        $current = $this->availability[$day][$period] ?? false;
        
        TeacherAvailability::updateOrCreate(
            ['teacher_id' => $this->teacher->id, 'day_of_week' => $day, 'period_id' => $period],
            ['available' => !$current]
        );

        $this->availability[$day][$period] = !$current;
    }

    public function render()
    {
        return view('livewire.teacher-availability-manager')->title(__('My Availability'));
    }
}
