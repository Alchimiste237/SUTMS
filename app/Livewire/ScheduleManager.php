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

    public $selectedDay;
    public $selectedPeriod;
    public $showAssignmentModal = false;
    public $availableAssignments = [];
    public $allTeachers = [];

    public function mount()
    {
        $this->classGroups = \App\Models\ClassGroup::all();
        if ($this->classGroups->isNotEmpty()) {
            $this->selectedClassGroupId = $this->classGroups->first()->id;
        }
        $this->allTeachers = \App\Models\Teacher::all();
        $this->refreshData();
    }

    public function openAssignmentModal($dayId, $periodId)
    {
        $this->selectedDay = $dayId;
        $this->selectedPeriod = $periodId;
        
        // Load assignments for this class group that have remaining hours
        if ($this->selectedClassGroupId && $this->selectedSchedule) {
            $semesterId = $this->selectedSchedule->academicWeek->semester_id;
            $this->availableAssignments = \App\Models\TeachingAssignment::where('class_group_id', $this->selectedClassGroupId)
                ->where('semester_id', $semesterId)
                ->with('teacher', 'subject')
                ->get();
        }

        $this->showAssignmentModal = true;
    }

    public function assign($assignmentId)
    {
        if (!$this->selectedSchedule || !$this->selectedDay || !$this->selectedPeriod) return;

        // Check for conflicts
        $assignment = \App\Models\TeachingAssignment::find($assignmentId);
        
        // 1. Is teacher busy?
        $isTeacherBusy = ScheduleEntry::where('weekly_schedule_id', $this->selectedSchedule->id)
            ->where('day_of_week', $this->selectedDay)
            ->where('period_id', $this->selectedPeriod)
            ->where(function($query) use ($assignment) {
                $query->whereHas('teachingAssignment', function($q) use ($assignment) {
                    $q->where('teacher_id', $assignment->teacher_id);
                })->orWhere('teacher_id', $assignment->teacher_id);
            })
            ->exists();

        if ($isTeacherBusy) {
            session()->flash('error', 'Teacher is already assigned to another class or personal working hour in this slot.');
            return;
        }

        // Remove existing entry for this class/day/period if any
        ScheduleEntry::where('weekly_schedule_id', $this->selectedSchedule->id)
            ->where('day_of_week', $this->selectedDay)
            ->where('period_id', $this->selectedPeriod)
            ->whereHas('teachingAssignment', function($q) {
                $q->where('class_group_id', $this->selectedClassGroupId);
            })
            ->delete();

        ScheduleEntry::create([
            'weekly_schedule_id' => $this->selectedSchedule->id,
            'teaching_assignment_id' => $assignmentId,
            'day_of_week' => $this->selectedDay,
            'period_id' => $this->selectedPeriod,
            'teacher_id' => $assignment->teacher_id,
            'class_group_id' => $this->selectedClassGroupId,
        ]);

        $this->showAssignmentModal = false;
        $this->loadSchedule($this->selectedSchedule->id);
    }

    public function markAsPersonalWorkingHour($teacherId)
    {
        if (!$this->selectedSchedule || !$this->selectedDay || !$this->selectedPeriod) return;

        // Is teacher busy?
        $isTeacherBusy = ScheduleEntry::where('weekly_schedule_id', $this->selectedSchedule->id)
            ->where('day_of_week', $this->selectedDay)
            ->where('period_id', $this->selectedPeriod)
            ->where(function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId)
                    ->orWhereHas('teachingAssignment', function($q) use ($teacherId) {
                        $q->where('teacher_id', $teacherId);
                    });
            })
            ->exists();

        if ($isTeacherBusy) {
            session()->flash('error', 'Teacher is already assigned to another class or personal working hour in this slot.');
            return;
        }

        ScheduleEntry::create([
            'weekly_schedule_id' => $this->selectedSchedule->id,
            'day_of_week' => $this->selectedDay,
            'period_id' => $this->selectedPeriod,
            'teacher_id' => $teacherId,
            'is_personal_working_hour' => true,
            'class_group_id' => $this->selectedClassGroupId, // Associate with class if marked from here
        ]);

        $this->showAssignmentModal = false;
        $this->loadSchedule($this->selectedSchedule->id);
    }

    public function fillGapsWithPersonalWorkingHours($scheduleId)
    {
        $schedule = WeeklySchedule::findOrFail($scheduleId);
        $teachers = \App\Models\Teacher::all();

        foreach ($teachers as $teacher) {
            foreach ($this->days as $dayId => $dayName) {
                foreach ($this->periods as $period) {
                    // Check if teacher is already busy in this slot
                    $isBusy = ScheduleEntry::where('weekly_schedule_id', $schedule->id)
                        ->where('day_of_week', $dayId)
                        ->where('period_id', $period)
                        ->where(function($query) use ($teacher) {
                            $query->where('teacher_id', $teacher->id)
                                ->orWhereHas('teachingAssignment', function($q) use ($teacher) {
                                    $q->where('teacher_id', $teacher->id);
                                });
                        })
                        ->exists();

                    if (!$isBusy) {
                        ScheduleEntry::create([
                            'weekly_schedule_id' => $schedule->id,
                            'day_of_week' => $dayId,
                            'period_id' => $period,
                            'teacher_id' => $teacher->id,
                            'is_personal_working_hour' => true,
                        ]);
                    }
                }
            }
        }

        session()->flash('message', 'All empty teacher slots have been marked as Personal Working Hours.');
        $this->loadSchedule($scheduleId);
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
        $this->fillGapsWithPersonalWorkingHours($scheduleId);
        $this->loadSchedule($scheduleId);
    }

    public function loadSchedule($scheduleId)
    {
        $this->selectedSchedule = WeeklySchedule::with(['entries' => function($query) {
            if ($this->selectedClassGroupId) {
                $query->where(function($q) {
                    $q->whereHas('teachingAssignment', function($inner) {
                        $inner->where('class_group_id', $this->selectedClassGroupId);
                    })->orWhere('class_group_id', $this->selectedClassGroupId);
                });
            }
            $query->with(['teachingAssignment.subject', 'teachingAssignment.teacher', 'teacher']);
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
