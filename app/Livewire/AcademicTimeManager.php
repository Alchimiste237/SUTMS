<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\AcademicWeek;
use Livewire\Component;
use Carbon\Carbon;

class AcademicTimeManager extends Component
{
    public $years, $semesters, $weeks;
    
    // New Year
    public $newYearName;

    // New Semester
    public $selectedYearId, $newSemesterName, $newSemesterStartDate, $newSemesterEndDate;

    // New Week Generation
    public $selectedSemesterId;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->years = AcademicYear::with('semesters.weeks')->get();
        if ($this->selectedYearId) {
            $this->semesters = Semester::where('academic_year_id', $this->selectedYearId)->get();
        } else {
            $this->semesters = collect();
        }
        
        if ($this->selectedSemesterId) {
            $this->weeks = AcademicWeek::where('semester_id', $this->selectedSemesterId)->orderBy('week_number')->get();
        } else {
            $this->weeks = collect();
        }
    }

    public function createYear()
    {
        $this->validate(['newYearName' => 'required|string|unique:academic_years,name']);
        AcademicYear::create(['name' => $this->newYearName, 'is_active' => true]);
        $this->newYearName = '';
        $this->refreshData();
    }

    public function createSemester()
    {
        $this->validate([
            'selectedYearId' => 'required|exists:academic_years,id',
            'newSemesterName' => 'required|string',
            'newSemesterStartDate' => 'required|date',
            'newSemesterEndDate' => 'required|date|after:newSemesterStartDate',
        ]);

        Semester::create([
            'academic_year_id' => $this->selectedYearId,
            'name' => $this->newSemesterName,
            'start_date' => $this->newSemesterStartDate,
            'end_date' => $this->newSemesterEndDate,
            'is_active' => true,
        ]);

        $this->newSemesterName = $this->newSemesterStartDate = $this->newSemesterEndDate = '';
        $this->refreshData();
    }

    public function generateWeeks()
    {
        $this->validate(['selectedSemesterId' => 'required|exists:semesters,id']);
        
        $semester = Semester::find($this->selectedSemesterId);
        $start = Carbon::parse($semester->start_date);
        $end = Carbon::parse($semester->end_date);
        
        $weekNumber = 1;
        $current = $start->copy()->startOfWeek(); // Start from the beginning of the week containing the start date

        // Clear existing weeks for this semester to avoid duplicates if re-generating
        AcademicWeek::where('semester_id', $semester->id)->delete();

        while ($current <= $end) {
            AcademicWeek::create([
                'semester_id' => $semester->id,
                'week_number' => $weekNumber++,
                'start_date' => $current->toDateString(),
                'end_date' => $current->copy()->endOfWeek()->toDateString(),
            ]);
            $current->addWeek();
        }

        $this->refreshData();
    }

    public function updatedSelectedYearId()
    {
        $this->refreshData();
    }

    public function updatedSelectedSemesterId()
    {
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.academic-time-manager')->title(__('Academic Time'));
    }
}
