<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div>
        <flux:heading size="xl" class="text-3xl font-bold tracking-tight">Teaching Assignments</flux:heading>
        <flux:subheading>Link faculty members to subjects and class groups for specific semesters.</flux:subheading>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create Form -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-6">
            <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400">
                <flux:icon icon="briefcase" size="md" />
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">New Assignment</h3>
            </div>

            <div class="space-y-4">
                <flux:select wire:model="teacherId" label="Faculty Member">
                    <option value="">Select Teacher...</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                    @endforeach
                </flux:select>
                
                <flux:select wire:model.live="subjectId" label="Course / Subject">
                    <option value="">Select Subject...</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                    @endforeach
                </flux:select>
                
                <flux:select wire:model="classGroupId" label="Target Class Group">
                    <option value="">Select Class...</option>
                    @foreach($classGroups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </flux:select>
                
                <flux:select wire:model="semesterId" label="Academic Semester">
                    <option value="">Select Semester...</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->academicYear->name }} — {{ $semester->name }}</option>
                    @endforeach
                </flux:select>
                
                <flux:input type="number" wire:model="requiredHours" label="Weekly Hours" placeholder="Automated from subject..." />
                
                <flux:button wire:click="assign" variant="primary" class="w-full">Create Assignment</flux:button>
            </div>
        </div>

        <!-- Assignment List -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="p-6 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Active Assignments</h3>
                <flux:badge size="sm" color="zinc" variant="subtle">{{ $assignments->count() }} Total</flux:badge>
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Faculty</flux:table.column>
                    <flux:table.column>Subject & Class</flux:table.column>
                    <flux:table.column>Semester</flux:table.column>
                    <flux:table.column>Load</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>
                
                <flux:table.rows>
                    @foreach($assignments as $assignment)
                        <flux:table.row>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-white">
                                {{ $assignment->teacher->first_name }} {{ $assignment->teacher->last_name }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="font-bold text-xs uppercase text-indigo-600 dark:text-indigo-400">{{ $assignment->subject->code }}</div>
                                <div class="text-sm">{{ $assignment->subject->name }}</div>
                                <div class="text-xs text-zinc-500">Group: {{ $assignment->classGroup->name }}</div>
                            </flux:table.cell>
                            <flux:table.cell class="text-xs">
                                {{ $assignment->semester->name }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" color="indigo" variant="subtle">{{ $assignment->required_hours }}h/week</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:button wire:click="deleteAssignment({{ $assignment->id }})" variant="subtle" color="red" size="sm" icon="trash" />
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</div>
