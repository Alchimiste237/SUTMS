<?php

namespace App\Livewire;

use App\Models\Level;
use App\Models\ClassGroup;
use App\Models\Subject;
use Livewire\Component;

class AcademicStructureManager extends Component
{
    public $levels, $classGroups, $subjects;
    
    // New entity properties
    public $newLevelName;
    public $newClassGroupName, $newClassGroupLevelId;
    public $newSubjectCode, $newSubjectName, $newSubjectCreditHours;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->levels = Level::all();
        $this->classGroups = ClassGroup::with('level')->get();
        $this->subjects = Subject::all();
    }

    public function createLevel()
    {
        $this->validate(['newLevelName' => 'required|string|max:255']);
        Level::create(['name' => $this->newLevelName]);
        $this->newLevelName = '';
        $this->refreshData();
    }

    public function createClassGroup()
    {
        $this->validate([
            'newClassGroupName' => 'required|string|max:255',
            'newClassGroupLevelId' => 'required|exists:levels,id'
        ]);
        ClassGroup::create([
            'name' => $this->newClassGroupName,
            'level_id' => $this->newClassGroupLevelId
        ]);
        $this->newClassGroupName = '';
        $this->refreshData();
    }

    public function createSubject()
    {
        $this->validate([
            'newSubjectCode' => 'required|string|unique:subjects,code',
            'newSubjectName' => 'required|string|max:255',
            'newSubjectCreditHours' => 'required|integer|min:1'
        ]);
        Subject::create([
            'code' => $this->newSubjectCode,
            'name' => $this->newSubjectName,
            'credit_hours' => $this->newSubjectCreditHours
        ]);
        $this->newSubjectCode = $this->newSubjectName = '';
        $this->newSubjectCreditHours = null;
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.academic-structure-manager')->title(__('Academic Structure'));
    }
}
