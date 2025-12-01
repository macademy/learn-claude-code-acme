@php
    $totalItems = $list->todoListItems->count();
    $completedCount = $list->todoListItems->where('completed', true)->count();
    $hasOverdue = $list->todoListItems->filter(fn($i) => !$i->completed && $i->due_date && $i->due_date->isPast())->isNotEmpty();
@endphp
<a
    href="{{ route('todo-lists.show', $list) }}"
    class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors min-h-[52px] {{ $isSelected ? 'bg-blue-50 dark:bg-blue-900/20' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
    @click="mobileMenuOpen = false"
>
    @if($list->color)
        <div class="w-6 h-6 rounded-full flex-shrink-0" style="background-color: {{ $list->color }}"></div>
    @else
        <div class="w-6 h-6 rounded-full bg-gray-300 dark:bg-gray-600 flex-shrink-0"></div>
    @endif
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
            <p class="text-base font-medium text-gray-900 dark:text-white truncate">{{ $list->name }}</p>
            @if($hasOverdue)
                <span class="text-red-500 dark:text-red-400 text-xs">!</span>
            @endif
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            @if($totalItems > 0)
                {{ $completedCount }}/{{ $totalItems }} done
            @else
                No items
            @endif
        </p>
    </div>
    {{-- Progress dots --}}
    @if($totalItems > 0)
        <div class="flex gap-0.5 flex-shrink-0">
            @php
                $displayDots = min(5, $totalItems);
                $filledDots = $totalItems > 0 ? round(($completedCount / $totalItems) * $displayDots) : 0;
            @endphp
            @for($i = 0; $i < $displayDots; $i++)
                <div class="w-1.5 h-1.5 rounded-full {{ $i < $filledDots ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
            @endfor
        </div>
    @endif
</a>
