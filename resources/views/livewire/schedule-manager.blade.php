<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-3xl font-bold tracking-tight">Schedule Management</flux:heading>
            <flux:subheading>Generate, review, and publish weekly university timetables.</flux:subheading>
        </div>

        <flux:button wire:click="initializeTestAvailabilities" variant="subtle" icon="clock" size="sm">
            Set All Teachers Available (Test)
        </flux:button>
    </div>

    @if (session()->has('message'))
        <flux:badge color="green" icon="check-circle">{{ session('message') }}</flux:badge>
    @endif

    <!-- Week Selection and Status -->
    <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-6">
        <div class="flex items-center gap-3 text-indigo-600 dark:text-indigo-400 border-b border-zinc-100 dark:border-zinc-800 pb-4">
            <flux:icon icon="squares-plus" size="md" />
            <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Active Weekly Schedules</h3>
        </div>

        <div class="flex flex-wrap gap-4 items-center">
            @foreach($schedules as $schedule)
                <div class="flex items-center gap-1 bg-zinc-50 dark:bg-zinc-950 p-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800">
                    <flux:button 
                        wire:click="loadSchedule({{ $schedule->id }})" 
                        :variant="$selectedSchedule && $selectedSchedule->id == $schedule->id ? 'primary' : 'subtle'"
                        size="sm"
                    >
                        Week {{ $schedule->academicWeek->week_number }}
                        <flux:badge size="sm" class="ml-2 px-1 text-[9px] uppercase" :color="$schedule->status === 'PUBLISHED' ? 'green' : ($schedule->status === 'DRAFT' ? 'orange' : 'zinc')">
                            {{ $schedule->status }}
                        </flux:badge>
                    </flux:button>

                    <div class="flex gap-1 px-1">
                        <flux:button wire:click="generate({{ $schedule->id }})" variant="subtle" color="indigo" size="sm" icon="sparkles" tooltip="Generate" />

                        @if($schedule->status == 'DRAFT')
                            <flux:button wire:click="publish({{ $schedule->id }})" variant="subtle" color="green" size="sm" icon="check" tooltip="Publish" />
                        @elseif($schedule->status == 'PUBLISHED')
                            <flux:button wire:click="archive({{ $schedule->id }})" variant="subtle" color="zinc" size="sm" icon="archive-box" tooltip="Archive" />
                        @endif
                    </div>
                </div>
            @endforeach

            @if($schedules->isEmpty() && $availableWeeks->isEmpty())
                <div class="w-full py-8 text-center bg-zinc-50 dark:bg-zinc-950 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800">
                    <flux:text class="mb-4">No weeks available. Initialize the academic calendar first.</flux:text>
                    <flux:button wire:click="initializeDemoData" variant="primary" color="green" size="sm">
                        Initialize Demo Calendar
                    </flux:button>
                </div>
            @endif
        </div>

        @if($availableWeeks->isNotEmpty())
            <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <h4 class="text-sm font-semibold text-zinc-500 mb-3 uppercase tracking-wider">Start a New Week</h4>
                <div class="flex items-end gap-3 max-w-lg">
                    <div class="flex-grow">
                        <flux:select wire:model="selectedWeekId" variant="subtle" size="sm">
                            <option value="">Choose an academic week...</option>
                            @foreach($availableWeeks as $week)
                                <option value="{{ $week->id }}">Week {{ $week->week_number }} (Starts {{ $week->start_date }})</option>
                            @endforeach
                        </flux:select>
                    </div>
                    <flux:button wire:click="createSchedule" variant="filled" size="sm">Create Schedule</flux:button>
                </div>
            </div>
        @endif
    </div>

    @if($selectedSchedule)
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <flux:heading size="lg">Timetable Review</flux:heading>
                    <flux:badge color="indigo" variant="subtle">Week {{ $selectedSchedule->academicWeek->week_number }}</flux:badge>
                </div>

                <div class="flex items-center gap-3">
                    <flux:text size="sm" class="font-medium">Filter by Class:</flux:text>
                    <div class="w-64">
                        <flux:select wire:model.live="selectedClassGroupId" size="sm" variant="subtle">
                            @foreach($classGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->level->name }})</option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-800/50">
                            <th class="border-b border-zinc-200 dark:border-zinc-700 p-4 text-left font-bold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-widest w-24">Period</th>
                            @foreach($days as $dayId => $dayName)
                                <th class="border-b border-zinc-200 dark:border-zinc-700 p-4 text-left font-bold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-widest">{{ $dayName }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach($periods as $period)
                            <tr>
                                <td class="p-4 font-black text-zinc-400 dark:text-zinc-600 bg-zinc-50/30 dark:bg-zinc-800/20 text-center">{{ $period }}</td>
                                @foreach($days as $dayId => $dayName)
                                    <td class="p-2 min-h-[100px] border-l border-zinc-100 dark:border-zinc-800">
                                        @php
                                            $entry = $selectedSchedule->entries->where('day_of_week', $dayId)->where('period_id', $period)->first();
                                        @endphp
                                        @if($entry)
                                            <div class="bg-white dark:bg-zinc-950 border border-indigo-100 dark:border-indigo-900/50 p-3 rounded-xl shadow-sm hover:shadow-md transition-shadow group">
                                                <div class="font-bold text-zinc-900 dark:text-white text-sm leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                    {{ $entry->teachingAssignment->subject->name }}
                                                </div>
                                                <div class="flex items-center gap-1.5 mt-2">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                                    <div class="text-zinc-600 dark:text-zinc-400 text-xs font-medium">
                                                        {{ $entry->teachingAssignment->teacher->first_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-16 w-full rounded-xl bg-zinc-50/50 dark:bg-zinc-900/20 border-2 border-dashed border-zinc-100 dark:border-zinc-800/50"></div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

