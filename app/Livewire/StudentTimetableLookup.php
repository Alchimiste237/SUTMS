<?php

namespace App\Livewire;

use App\Models\WeeklySchedule;
use App\Models\ClassGroup;
use App\Models\AcademicWeek;
use Livewire\Component;

class StudentTimetableLookup extends Component
{
    public $schedules;
    public $classGroups;
    
    public $selectedScheduleId;
    public $selectedClassGroupId;
    
    public $selectedSchedule;
    public $days = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri'];
    public $periods = ['P1', 'P2', 'P3', 'P4'];

    public function mount()
    {
        $this->classGroups = ClassGroup::all();
        
        // Only show published schedules to students
        $this->schedules = WeeklySchedule::with('academicWeek')
            ->where('status', 'PUBLISHED')
            ->get()
            ->sortByDesc('academicWeek.start_date');

        if ($this->schedules->isNotEmpty()) {
            $this->selectedScheduleId = $this->schedules->first()->id;
        }
    }

    public function updatedSelectedScheduleId()
    {
        $this->loadTimetable();
    }

    public function updatedSelectedClassGroupId()
    {
        $this->loadTimetable();
    }

    public function loadTimetable()
    {
        if (!$this->selectedScheduleId || !$this->selectedClassGroupId) {
            $this->selectedSchedule = null;
            return;
        }

        $this->selectedSchedule = WeeklySchedule::with(['entries' => function($query) {
            $query->where(function($q) {
                $q->whereHas('teachingAssignment', function($inner) {
                    $inner->where('class_group_id', $this->selectedClassGroupId);
                })->orWhere('class_group_id', $this->selectedClassGroupId);
            })->with('teachingAssignment.subject', 'teachingAssignment.teacher');
        }])->find($this->selectedScheduleId);
    }

    public function render()
    {
        // Check if layout exists, otherwise use a default or none if public
        return view('livewire.student-timetable-lookup')->layout('layouts.app')->title(__('Class Timetable'));
    }
}
