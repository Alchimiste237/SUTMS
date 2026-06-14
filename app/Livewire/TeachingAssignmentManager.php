<?php

namespace App\Livewire;

use App\Models\TeachingAssignment;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassGroup;
use App\Models\Semester;
use Livewire\Component;

class TeachingAssignmentManager extends Component
{
    public $assignments;
    public $teachers, $subjects, $classGroups, $semesters;
    
    public $teacherId, $subjectId, $classGroupId, $semesterId, $requiredHours;

    public function mount()
    {
        $this->teachers = Teacher::all();
        $this->subjects = Subject::all();
        $this->classGroups = ClassGroup::all();
        $this->semesters = Semester::with('academicYear')->get();
        $this->refreshData();
    }

    public function updatedSubjectId($value)
    {
        if ($value) {
            $subject = Subject::find($value);
            if ($subject) {
                $this->requiredHours = $subject->credit_hours;
            }
        }
    }

    public function refreshData()
    {
        $this->assignments = TeachingAssignment::with(['teacher', 'subject', 'classGroup', 'semester'])->get();
    }

    public function assign()
    {
        $this->validate([
            'teacherId' => 'required|exists:teachers,id',
            'subjectId' => 'required|exists:subjects,id',
            'classGroupId' => 'required|exists:class_groups,id',
            'semesterId' => 'required|exists:semesters,id',
            'requiredHours' => 'required|integer|min:1',
        ]);

        TeachingAssignment::create([
            'teacher_id' => $this->teacherId,
            'subject_id' => $this->subjectId,
            'class_group_id' => $this->classGroupId,
            'semester_id' => $this->semesterId,
            'required_hours' => $this->requiredHours,
            'remaining_hours' => $this->requiredHours,
            'completed_hours' => 0,
        ]);

        $this->reset(['teacherId', 'subjectId', 'classGroupId', 'semesterId', 'requiredHours']);
        $this->refreshData();
    }

    public function deleteAssignment($id)
    {
        TeachingAssignment::destroy($id);
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.teaching-assignment-manager')->title(__('Teaching Assignments'));
    }
}
