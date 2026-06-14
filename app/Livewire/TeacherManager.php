<?php

namespace App\Livewire;

use App\Models\Teacher;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class TeacherManager extends Component
{
    public $teachers;
    public $name, $email, $password, $staffCode, $firstName, $lastName, $teacherType;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->teachers = Teacher::with('user')->get();
    }

    public function createTeacher()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'staffCode' => 'required|string|unique:teachers,staff_code',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'teacherType' => 'required|in:INTERNAL,EXTERNAL',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        
        $role = Role::firstOrCreate(['name' => 'TEACHER']);
        $user->assignRole($role);

        Teacher::create([
            'user_id' => $user->id,
            'staff_code' => $this->staffCode,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'teacher_type' => $this->teacherType,
        ]);

        $this->reset(['name', 'email', 'password', 'staffCode', 'firstName', 'lastName', 'teacherType']);
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.teacher-manager')->title(__('Teacher Management'));
    }
}
