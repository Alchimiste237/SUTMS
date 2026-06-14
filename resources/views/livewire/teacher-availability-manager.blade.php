<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div>
        <flux:heading size="xl" class="text-3xl font-bold tracking-tight">Teaching Availability</flux:heading>
        <flux:subheading>Define your active hours for the current semester. The system uses this to build your personalized schedule.</flux:subheading>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="p-6 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between bg-zinc-50/50 dark:bg-zinc-800/20">
            <div class="flex items-center gap-3">
                <flux:icon icon="clock" size="md" class="text-indigo-600 dark:text-indigo-400" />
                <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Weekly Availability Grid</h3>
            </div>
            <flux:badge color="indigo" variant="subtle">Interactive Grid</flux:badge>
        </div>

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-zinc-50 dark:bg-zinc-800/50">
                    <th class="border-b border-zinc-200 dark:border-zinc-700 p-4 text-left font-bold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-widest w-32">Day</th>
                    @foreach($periods as $period)
                        <th class="border-b border-zinc-200 dark:border-zinc-700 p-4 text-center font-bold text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-widest">{{ $period }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @foreach($days as $dayId => $dayName)
                    <tr>
                        <td class="p-4 font-bold text-zinc-900 dark:text-white bg-zinc-50/30 dark:bg-zinc-800/20 border-r border-zinc-100 dark:border-zinc-800">{{ $dayName }}</td>
                        @foreach($periods as $period)
                            @php
                                $isAvailable = $availability[$dayId][$period] ?? false;
                            @endphp
                            <td class="p-2 text-center cursor-pointer transition-all duration-200 hover:scale-[0.98]"
                                wire:click="toggleAvailability({{ $dayId }}, '{{ $period }}')">
                                <div class="flex flex-col items-center justify-center gap-1.5 py-3 rounded-xl border-2 transition-colors
                                    {{ $isAvailable 
                                        ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400' 
                                        : 'bg-zinc-50 dark:bg-zinc-900/50 border-zinc-100 dark:border-zinc-800 text-zinc-400 dark:text-zinc-600' }}">
                                    
                                    <flux:icon :icon="$isAvailable ? 'check-circle' : 'no-symbol'" size="sm" />
                                    <span class="text-[10px] font-bold uppercase tracking-tighter">{{ $isAvailable ? 'Available' : 'Busy' }}</span>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center gap-4 p-4 bg-indigo-50 dark:bg-indigo-900/10 rounded-2xl border border-indigo-100 dark:border-indigo-900/30">
        <flux:icon icon="information-circle" size="md" class="text-indigo-600 dark:text-indigo-400 flex-shrink-0" />
        <flux:text size="sm" color="indigo">
            <strong>Pro Tip:</strong> Click any cell to toggle your status. Changes are saved automatically and reflected in the next schedule generation.
        </flux:text>
    </div>
</div>
