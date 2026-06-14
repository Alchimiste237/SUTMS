@props(['title', 'description', 'href', 'icon'])

<a href="{{ $href }}" class="group relative block p-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-500/50 dark:hover:border-indigo-500/50 transition-all duration-200 overflow-hidden" wire:navigate>
    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-500/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
    
    <div class="relative z-10">
        <div class="mb-5 inline-flex items-center justify-center w-12 h-12 bg-indigo-600 rounded-xl text-white shadow-lg shadow-indigo-200 dark:shadow-none group-hover:scale-110 transition-transform duration-200">
            @if(isset($icon) && strlen($icon) > 1)
                <flux:icon :icon="$icon" size="md" />
            @else
                <span class="text-lg font-bold">{{ strtoupper($icon ?? substr($title, 0, 1)) }}</span>
            @endif
        </div>
        
        <h3 class="mb-2 text-xl font-bold tracking-tight text-zinc-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
            {{ $title }}
        </h3>
        
        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
            {{ $description }}
        </p>
        
        <div class="mt-6 flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity">
            Open module <flux:icon icon="arrow-right" size="sm" class="ml-2" />
        </div>
    </div>
</a>
