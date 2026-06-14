<?php

namespace App\Livewire;

use App\Models\WeeklySchedule;
use App\Models\ScheduleEntry;
use App\Services\ScheduleGeneratorService;
use Livewire\Component;

class ScheduleManager extends Component
{
    public $schedules;
    public $availableWeeks;
    public $classGroups;
    public $selectedWeekId;
    public $selectedClassGroupId;
    public $selectedSchedule;
    public $days = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri'];
    public $periods = ['P1', 'P2', 'P3', 'P4'];

    public function mount()
    {
        $this->classGroups = \App\Models\ClassGroup::all();
        if ($this->classGroups->isNotEmpty()) {
            $this->selectedClassGroupId = $this->classGroups->first()->id;
        }
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->schedules = WeeklySchedule::with('academicWeek')->get();
        
        // Get weeks that don't have a schedule yet
        $scheduledWeekIds = $this->schedules->pluck('academic_week_id')->toArray();
        $this->availableWeeks = \App\Models\AcademicWeek::whereNotIn('id', $scheduledWeekIds)->get();
    }

    public function updatedSelectedClassGroupId()
    {
        if ($this->selectedSchedule) {
            $this->loadSchedule($this->selectedSchedule->id);
        }
    }

    public function createSchedule()
    {
        $this->validate([
            'selectedWeekId' => 'required|exists:academic_weeks,id'
        ]);

        WeeklySchedule::create([
            'academic_week_id' => $this->selectedWeekId,
            'status' => 'DRAFT'
        ]);

        $this->selectedWeekId = null;
        $this->refreshData();
    }

    public function initializeDemoData()
    {
        \Illuminate\Support\Facades\DB::transaction(function () {
            $year = \App\Models\AcademicYear::firstOrCreate(['name' => '2026-2027'], ['is_active' => true]);
            $semester = \App\Models\Semester::firstOrCreate(
                ['academic_year_id' => $year->id, 'name' => 'Semester 1'],
                ['start_date' => '2026-09-01', 'end_date' => '2027-01-31', 'is_active' => true]
            );
            $week = \App\Models\AcademicWeek::firstOrCreate(
                ['semester_id' => $semester->id, 'week_number' => 1],
                ['start_date' => '2026-09-01', 'end_date' => '2026-09-07']
            );
            WeeklySchedule::firstOrCreate(['academic_week_id' => $week->id], ['status' => 'DRAFT']);
        });

        $this->refreshData();
    }

    public function initializeTestAvailabilities()
    {
        $teachers = \App\Models\Teacher::all();
        foreach ($teachers as $teacher) {
            foreach ($this->days as $dayId => $dayName) {
                foreach ($this->periods as $period) {
                    \App\Models\TeacherAvailability::updateOrCreate(
                        ['teacher_id' => $teacher->id, 'day_of_week' => $dayId, 'period_id' => $period],
                        ['available' => true]
                    );
                }
            }
        }
        session()->flash('message', 'All teachers set to available for all slots.');
    }

    public function generate($scheduleId)
    {
        (new ScheduleGeneratorService())->generate($scheduleId);
        $this->loadSchedule($scheduleId);
    }

    public function loadSchedule($scheduleId)
    {
        $this->selectedSchedule = WeeklySchedule::with(['entries' => function($query) {
            if ($this->selectedClassGroupId) {
                $query->whereHas('teachingAssignment', function($q) {
                    $q->where('class_group_id', $this->selectedClassGroupId);
                });
            }
            $query->with('teachingAssignment.subject', 'teachingAssignment.teacher');
        }])->find($scheduleId);
    }

    public function publish($scheduleId)
    {
        $schedule = WeeklySchedule::findOrFail($scheduleId);
        $schedule->update(['status' => 'PUBLISHED']);
        $this->refreshData();
        if ($this->selectedSchedule && $this->selectedSchedule->id == $scheduleId) {
            $this->loadSchedule($scheduleId);
        }
    }

    public function archive($scheduleId)
    {
        $schedule = WeeklySchedule::findOrFail($scheduleId);
        $schedule->update(['status' => 'ARCHIVED']);
        $this->refreshData();
        if ($this->selectedSchedule && $this->selectedSchedule->id == $scheduleId) {
            $this->loadSchedule($scheduleId);
        }
    }

    public function render()
    {
        return view('livewire.schedule-manager')->title(__('Schedule Management'));
    }
}
