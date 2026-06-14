<x-layouts::app :title="__('Dashboard')">
    <div class="flex flex-col gap-8 p-6 max-w-7xl mx-auto">
        <div>
            <flux:heading size="xl" class="text-3xl font-bold tracking-tight">Welcome, {{ auth()->user()->name }}</flux:heading>
            <flux:subheading class="mt-1">
                You are logged in as <flux:badge color="indigo" size="sm" class="ml-1 uppercase tracking-wider">{{ auth()->user()->getRoleNames()->first() }}</flux:badge>
            </flux:subheading>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            {{-- ADMIN Dashboard Tools --}}
            @role('ADMIN')
                <x-dashboard-card title="Academic Structure" description="Manage levels, groups, and subjects." href="{{ route('academic-structure') }}" icon="academic-cap" />
                <x-dashboard-card title="Academic Time" description="Manage years, semesters, and weeks." href="{{ route('academic-time') }}" icon="calendar-days" />
                <x-dashboard-card title="Teaching Assignments" description="Assign teachers to subjects and classes." href="{{ route('teaching-assignments') }}" icon="briefcase" />
                <x-dashboard-card title="Schedule Management" description="Generate and adjust timetables." href="{{ route('schedule-management') }}" icon="squares-plus" />
                <x-dashboard-card 
                    title="Teacher Management" 
                    description="Manage teacher profiles and assignments." 
                    href="{{ route('teacher-management') }}" 
                    icon="users" />
            @endrole

            {{-- TEACHER Dashboard Tools --}}
            @role('TEACHER')
                <x-dashboard-card title="My Availability" description="Set your teaching availability." href="{{ route('my-availability') }}" icon="clock" />
                <x-dashboard-card title="My Timetable" description="View your assigned courses and schedule." href="{{ route('my-timetable') }}" icon="table-cells" />
            @endrole

            {{-- STUDENT Dashboard Tools --}}
            @role('STUDENT')
                <x-dashboard-card title="Class Timetable" description="Lookup timetable for your class." href="{{ route('timetable') }}" icon="magnifying-glass" />
            @endrole
        </div>
    </div>
</x-layouts::app>
