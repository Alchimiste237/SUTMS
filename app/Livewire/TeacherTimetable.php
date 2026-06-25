<?php

namespace App\Livewire;

use App\Models\WeeklySchedule;
use App\Models\Teacher;
use App\Models\AcademicWeek;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TeacherTimetable extends Component
{
    public $schedules;
    public $selectedScheduleId;
    public $selectedSchedule;
    public $teacher;
    public $days = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri'];
    public $periods = ['P1', 'P2', 'P3', 'P4'];

    public function mount()
    {
        $this->teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        // Load published or draft schedules that have entries
        $this->schedules = WeeklySchedule::with('academicWeek')
            ->whereIn('status', ['PUBLISHED', 'DRAFT'])
            ->get()
            ->sortByDesc('academicWeek.start_date');

        if ($this->schedules->isNotEmpty()) {
            $this->selectedScheduleId = $this->schedules->first()->id;
            $this->loadSchedule();
        }
    }

    public function updatedSelectedScheduleId()
    {
        $this->loadSchedule();
    }

    public function loadSchedule()
    {
        if (!$this->selectedScheduleId) {
            $this->selectedSchedule = null;
            return;
        }

        $this->selectedSchedule = WeeklySchedule::with(['entries' => function($query) {
            $query->where(function($q) {
                $q->whereHas('teachingAssignment', function($inner) {
                    $inner->where('teacher_id', $this->teacher->id);
                })->orWhere('teacher_id', $this->teacher->id);
            })->with(['teachingAssignment.subject', 'teachingAssignment.classGroup']);
        }])->find($this->selectedScheduleId);
    }

    public function render()
    {
        return view('livewire.teacher-timetable')->title(__('My Timetable'));
    }
}
