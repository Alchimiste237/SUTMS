<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="text-3xl font-bold tracking-tight">Class Timetable Lookup</flux:heading>
            <flux:subheading>Select your week and class group to view your official university schedule.</flux:subheading>
        </div>
        @guest
            <flux:button href="{{ route('login') }}" variant="subtle" icon="user" size="sm">Staff Login</flux:button>
        @endguest
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800">
        <flux:select wire:model.live="selectedScheduleId" label="Academic Week" icon="calendar">
            <option value="">Choose Week...</option>
            @foreach($schedules as $schedule)
                <option value="{{ $schedule->id }}">Week {{ $schedule->academicWeek->week_number }} (Starts {{ \Carbon\Carbon::parse($schedule->academicWeek->start_date)->format('M d') }})</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="selectedClassGroupId" label="Target Class Group" icon="user-group">
            <option value="">Choose Class...</option>
            @foreach($classGroups as $group)
                <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->level->name }})</option>
            @endforeach
        </flux:select>
    </div>

    @if($selectedSchedule)
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-emerald-50/50 dark:bg-emerald-900/10">
                        <th class="border-b border-zinc-200 dark:border-zinc-700 p-4 text-left font-bold text-emerald-700 dark:text-emerald-400 text-xs uppercase tracking-widest w-24">Period</th>
                        @foreach($days as $dayId => $dayName)
                            <th class="border-b border-zinc-200 dark:border-zinc-700 p-4 text-left font-bold text-emerald-700 dark:text-emerald-400 text-xs uppercase tracking-widest">{{ $dayName }}</th>
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
                                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 p-3 rounded-xl shadow-sm group">
                                            <div class="font-bold text-emerald-900 dark:text-emerald-100 text-sm leading-tight uppercase tracking-tight">
                                                {{ $entry->teachingAssignment->subject->name }}
                                            </div>
                                            <div class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $entry->teachingAssignment->subject->code }}</div>
                                            
                                            <div class="flex items-center gap-1.5 mt-3 pt-2 border-t border-emerald-100 dark:border-emerald-800/50">
                                                <div class="text-zinc-600 dark:text-zinc-400 text-[11px] font-medium italic">
                                                    {{ $entry->teachingAssignment->teacher->first_name }} {{ $entry->teachingAssignment->teacher->last_name }}
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
    @elseif($selectedClassGroupId)
        <div class="py-20 text-center bg-zinc-50 dark:bg-zinc-900/50 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800">
            <flux:icon icon="information-circle" size="xl" class="mx-auto text-zinc-300 mb-4" />
            <flux:heading>No published schedule</flux:heading>
            <flux:text>There is no published timetable for this class in the selected week.</flux:text>
        </div>
    @else
        <div class="py-20 text-center bg-zinc-50 dark:bg-zinc-900/50 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800">
            <flux:icon icon="magnifying-glass" size="xl" class="mx-auto text-zinc-300 mb-4" />
            <flux:heading>Ready to lookup</flux:heading>
            <flux:text>Please select a week and your class group to view the timetable.</flux:text>
        </div>
    @endif
</div>
