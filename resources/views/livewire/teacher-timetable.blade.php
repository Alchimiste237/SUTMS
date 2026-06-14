<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div>
        <flux:heading size="xl" class="text-3xl font-bold tracking-tight">My Timetable</flux:heading>
        <flux:subheading>Your personalized teaching schedule for the selected academic week.</flux:subheading>
    </div>

    @if($schedules->isEmpty())
        <div class="py-20 text-center bg-zinc-50 dark:bg-zinc-900/50 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-800">
            <flux:icon icon="information-circle" size="xl" class="mx-auto text-zinc-300 mb-4" />
            <flux:heading>No active schedules</flux:heading>
            <flux:text>There are currently no schedules available to view.</flux:text>
        </div>
    @else
        <div class="max-w-xs p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800">
            <flux:select wire:model.live="selectedScheduleId" label="Select Week" icon="calendar">
                @foreach($schedules as $schedule)
                    <option value="{{ $schedule->id }}">Week {{ $schedule->academicWeek->week_number }} (Starts {{ \Carbon\Carbon::parse($schedule->academicWeek->start_date)->format('M d') }})</option>
                @endforeach
            </flux:select>
        </div>

        @if($selectedSchedule)
            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-indigo-50/50 dark:bg-indigo-900/10">
                            <th class="border-b border-zinc-200 dark:border-zinc-700 p-4 text-left font-bold text-indigo-700 dark:text-indigo-400 text-xs uppercase tracking-widest w-24">Period</th>
                            @foreach($days as $dayId => $dayName)
                                <th class="border-b border-zinc-200 dark:border-zinc-700 p-4 text-left font-bold text-indigo-700 dark:text-indigo-400 text-xs uppercase tracking-widest">{{ $dayName }}</th>
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
                                            <div class="bg-white dark:bg-zinc-950 border border-indigo-100 dark:border-indigo-900/50 p-4 rounded-xl shadow-sm hover:shadow-md transition-all group">
                                                <div class="font-bold text-indigo-900 dark:text-indigo-100 text-sm leading-tight uppercase">
                                                    {{ $entry->teachingAssignment->subject->name }}
                                                </div>
                                                <div class="flex items-center gap-2 mt-3 pt-2 border-t border-zinc-50 dark:border-zinc-800">
                                                    <flux:icon icon="user-group" size="xs" class="text-zinc-400" />
                                                    <div class="text-zinc-600 dark:text-zinc-400 text-xs font-bold">
                                                        Class: {{ $entry->teachingAssignment->classGroup->name }}
                                                    </div>
                                                </div>
                                                <div class="text-[10px] font-mono text-zinc-400 mt-1 uppercase">{{ $entry->teachingAssignment->subject->code }}</div>
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
        @endif
    @endif
</div>
