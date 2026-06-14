<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div>
        <flux:heading size="xl" class="text-3xl font-bold tracking-tight">Academic Structure</flux:heading>
        <flux:subheading>Manage the foundation of your university: Levels, Class Groups, and Subjects.</flux:subheading>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Levels -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-6">
            <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400">
                <flux:icon icon="academic-cap" size="md" />
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Academic Levels</h3>
            </div>
            
            <div class="space-y-3">
                <flux:input wire:model="newLevelName" label="New Level Name" placeholder="e.g. Year 1, Masters..." />
                <flux:button wire:click="createLevel" variant="primary" class="w-full">Add Level</flux:button>
            </div>
            
            <div class="border-t border-zinc-100 dark:border-zinc-800 pt-4">
                <flux:table>
                    <flux:table.rows>
                        @foreach($levels as $level)
                            <flux:table.row>
                                <flux:table.cell class="font-medium">{{ $level->name }}</flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        </div>

        <!-- Class Groups -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-6">
            <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400">
                <flux:icon icon="user-group" size="md" />
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Class Groups</h3>
            </div>

            <div class="space-y-3">
                <flux:input wire:model="newClassGroupName" label="Group Name" placeholder="e.g. Group A, Section 1..." />
                <flux:select wire:model="newClassGroupLevelId" label="Level">
                    <option value="">Assign to Level...</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </flux:select>
                <flux:button wire:click="createClassGroup" variant="primary" class="w-full">Add Class Group</flux:button>
            </div>

            <div class="border-t border-zinc-100 dark:border-zinc-800 pt-4">
                <flux:table>
                    <flux:table.rows>
                        @foreach($classGroups as $group)
                            <flux:table.row>
                                <flux:table.cell class="font-medium">
                                    {{ $group->name }}
                                    <flux:text size="sm" class="block text-zinc-500">{{ $group->level->name }}</flux:text>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        </div>

        <!-- Subjects -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-6">
            <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400">
                <flux:icon icon="book-open" size="md" />
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Subjects</h3>
            </div>

            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <flux:input wire:model="newSubjectCode" label="Code" placeholder="CS101" />
                    <flux:input wire:model="newSubjectCreditHours" type="number" label="Hours" placeholder="3" />
                </div>
                <flux:input wire:model="newSubjectName" label="Full Name" placeholder="e.g. Data Structures" />
                <flux:button wire:click="createSubject" variant="primary" class="w-full">Add Subject</flux:button>
            </div>

            <div class="border-t border-zinc-100 dark:border-zinc-800 pt-4">
                <flux:table>
                    <flux:table.rows>
                        @foreach($subjects as $subject)
                            <flux:table.row>
                                <flux:table.cell class="font-medium">
                                    <div class="flex justify-between items-center">
                                        <span>{{ $subject->name }}</span>
                                        <flux:badge size="sm" color="zinc" variant="subtle">{{ $subject->code }}</flux:badge>
                                    </div>
                                    <flux:text size="sm" class="text-zinc-500">{{ $subject->credit_hours }} hours per week</flux:text>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        </div>
    </div>
</div>
