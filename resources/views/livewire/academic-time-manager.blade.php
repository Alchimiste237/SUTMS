<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div>
        <flux:heading size="xl" class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Academic Calendar</flux:heading>
        <flux:subheading>Configure academic years, semesters, and auto-generate weekly structures.</flux:subheading>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Academic Years -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-6">
            <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400">
                <flux:icon icon="calendar" size="md" />
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Academic Years</h3>
            </div>
            
            <div class="flex items-end gap-2">
                <div class="flex-grow">
                    <flux:input wire:model="newYearName" placeholder="e.g. 2026-2027" label="New Year Name" />
                </div>
                <flux:button wire:click="createYear" variant="primary">Add Year</flux:button>
            </div>
            
            <flux:table>
                <flux:table.rows>
                    @foreach($years as $year)
                        <flux:table.row>
                            <flux:table.cell class="font-bold">{{ $year->name }}</flux:table.cell>
                            <flux:table.cell>
                                @if($year->is_active)
                                    <flux:badge color="green" size="sm" variant="pill">Active</flux:badge>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>

        <!-- Semesters -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-6">
            <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400">
                <flux:icon icon="clock" size="md" />
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Semesters</h3>
            </div>

            <div class="space-y-4">
                <flux:select wire:model.live="selectedYearId" label="Select Academic Year">
                    <option value="">Choose Year...</option>
                    @foreach($years as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </flux:select>
                
                @if($selectedYearId)
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-950 rounded-xl border border-zinc-100 dark:border-zinc-800 space-y-3">
                        <flux:input wire:model="newSemesterName" label="Semester Name" placeholder="e.g. Fall Semester" />
                        <div class="grid grid-cols-2 gap-2">
                            <flux:input type="date" wire:model="newSemesterStartDate" label="Start Date" />
                            <flux:input type="date" wire:model="newSemesterEndDate" label="End Date" />
                        </div>
                        <flux:button wire:click="createSemester" variant="primary" class="w-full">Create Semester</flux:button>
                    </div>
                @endif
            </div>
            
            <flux:table>
                <flux:table.rows>
                    @foreach($semesters as $semester)
                        <flux:table.row>
                            <flux:table.cell class="font-medium">{{ $semester->name }}</flux:table.cell>
                            <flux:table.cell class="text-xs text-zinc-500 uppercase tracking-tighter">
                                {{ \Carbon\Carbon::parse($semester->start_date)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($semester->end_date)->format('M d, Y') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>

        <!-- Academic Weeks -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-6 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400">
                    <flux:icon icon="hashtag" size="md" />
                    <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Weekly Structure</h3>
                </div>
                
                @if($selectedSemesterId)
                    <flux:button wire:click="generateWeeks" variant="subtle" size="sm" icon="arrow-path">
                        Regenerate Weeks
                    </flux:button>
                @endif
            </div>

            <div class="max-w-md">
                <flux:select wire:model.live="selectedSemesterId" label="Select Semester to View Weeks">
                    <option value="">Choose Semester...</option>
                    @foreach($years as $year)
                        <optgroup label="{{ $year->name }}">
                            @foreach($year->semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </flux:select>
            </div>
            
            @if($weeks->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach($weeks as $week)
                        <div class="relative group border border-zinc-200 dark:border-zinc-800 p-4 rounded-xl text-center bg-white dark:bg-zinc-950 shadow-sm hover:border-indigo-500/50 transition-all">
                            <div class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mb-1 uppercase">Week {{ $week->week_number }}</div>
                            <div class="text-sm font-medium text-zinc-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($week->start_date)->format('M d') }}
                            </div>
                            <div class="text-[10px] text-zinc-500">
                                to {{ \Carbon\Carbon::parse($week->end_date)->format('M d') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center bg-zinc-50 dark:bg-zinc-950 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800">
                    <flux:text>Select a semester to see the weekly structure.</flux:text>
                </div>
            @endif
        </div>
    </div>
</div>
