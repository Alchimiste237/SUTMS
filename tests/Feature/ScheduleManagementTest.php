<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\AcademicWeek;
use App\Models\WeeklySchedule;
use App\Models\ClassGroup;
use App\Models\Level;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\ScheduleEntry;
use App\Livewire\ScheduleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ScheduleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $user = User::factory()->create();
        $this->actingAs($user);

        $level = Level::create(['name' => 'Year 1']);
        $this->classGroup = ClassGroup::create(['level_id' => $level->id, 'name' => 'Class A']);
        $this->teacher = Teacher::create([
            'user_id' => $user->id, 
            'staff_code' => 'T1', 
            'first_name' => 'John', 
            'last_name' => 'Doe',
            'teacher_type' => 'INTERNAL'
        ]);
        $this->subject = Subject::create(['code' => 'CS101', 'name' => 'Computer Science', 'credit_hours' => 3]);
        
        $year = AcademicYear::create(['name' => '2026-2027', 'is_active' => true]);
        $this->semester = Semester::create(['academic_year_id' => $year->id, 'name' => 'Semester 1', 'start_date' => '2026-09-01', 'end_date' => '2027-01-31', 'is_active' => true]);
        $this->week = AcademicWeek::create(['semester_id' => $this->semester->id, 'week_number' => 1, 'start_date' => '2026-09-01', 'end_date' => '2026-09-07']);
        $this->schedule = WeeklySchedule::create(['academic_week_id' => $this->week->id, 'status' => 'DRAFT']);
        
        $this->assignment = TeachingAssignment::create([
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'class_group_id' => $this->classGroup->id,
            'semester_id' => $this->semester->id,
            'required_hours' => 3,
            'completed_hours' => 0,
            'remaining_hours' => 3
        ]);
    }

    public function test_can_manually_assign_a_slot()
    {
        Livewire::test(ScheduleManager::class)
            ->set('selectedSchedule', $this->schedule)
            ->set('selectedClassGroupId', $this->classGroup->id)
            ->set('selectedDay', 1)
            ->set('selectedPeriod', 'P1')
            ->call('assign', $this->assignment->id);

        $this->assertDatabaseHas('schedule_entries', [
            'weekly_schedule_id' => $this->schedule->id,
            'teaching_assignment_id' => $this->assignment->id,
            'day_of_week' => 1,
            'period_id' => 'P1',
            'is_personal_working_hour' => false
        ]);
    }

    public function test_can_mark_as_personal_working_hour()
    {
        Livewire::test(ScheduleManager::class)
            ->set('selectedSchedule', $this->schedule)
            ->set('selectedClassGroupId', $this->classGroup->id)
            ->set('selectedDay', 2)
            ->set('selectedPeriod', 'P2')
            ->call('markAsPersonalWorkingHour', $this->teacher->id);

        $this->assertDatabaseHas('schedule_entries', [
            'weekly_schedule_id' => $this->schedule->id,
            'teacher_id' => $this->teacher->id,
            'day_of_week' => 2,
            'period_id' => 'P2',
            'is_personal_working_hour' => true
        ]);
    }

    public function test_can_fill_gaps_with_personal_working_hours()
    {
        // First assign one slot
        ScheduleEntry::create([
            'weekly_schedule_id' => $this->schedule->id,
            'teaching_assignment_id' => $this->assignment->id,
            'day_of_week' => 1,
            'period_id' => 'P1',
            'teacher_id' => $this->teacher->id,
            'class_group_id' => $this->classGroup->id
        ]);

        Livewire::test(ScheduleManager::class)
            ->call('fillGapsWithPersonalWorkingHours', $this->schedule->id);

        // Teacher has 5 days * 4 periods = 20 slots.
        // 1 is a lesson, so 19 should be PWH.
        $this->assertEquals(19, ScheduleEntry::where('teacher_id', $this->teacher->id)
            ->where('is_personal_working_hour', true)
            ->count());
    }
}
