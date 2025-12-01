<div class="group py-3 flex items-center gap-3 border-b border-gray-100 dark:border-gray-800 last:border-b-0 ml-3">
    {{-- Checkbox --}}
    <form action="{{ route('todo-lists.items.toggle', [$list, $item]) }}" method="POST">
        @csrf
        <button
            type="submit"
            class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $item->completed ? 'bg-blue-500 border-blue-500' : 'border-gray-300 dark:border-gray-600 hover:border-blue-500' }}"
        >
            @if($item->completed)
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            @endif
        </button>
    </form>

    {{-- Content --}}
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="{{ $item->completed ? 'line-through text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-white' }}">
                {{ $item->title }}
            </span>
            @if($item->due_date)
                <span class="text-xs px-2 py-0.5 rounded-full {{ $item->due_date->isPast() && !$item->completed ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                    {{ $item->due_date->format('M j') }}
                    @if($item->due_date->isPast() && !$item->completed)
                        <span class="ml-1">!</span>
                    @endif
                </span>
            @endif
        </div>

        {{-- Edit form (hidden by default) --}}
        <div id="edit-item-form-{{ $item->id }}" class="hidden mt-2">
            <form action="{{ route('todo-lists.items.update', [$list, $item]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('todo-lists.items._form', ['item' => $item])
            </form>
        </div>
    </div>

    {{-- Actions - visible on mobile (subtle), hover on desktop --}}
    <div class="flex gap-1 flex-shrink-0 opacity-50 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
        <button
            onclick="document.getElementById('edit-item-form-{{ $item->id }}').classList.toggle('hidden')"
            class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
        >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
            </svg>
        </button>
        <form action="{{ route('todo-lists.items.destroy', [$list, $item]) }}" method="POST" onsubmit="return confirm('Delete this item?')">
            @csrf
            @method('DELETE')
            <button
                type="submit"
                class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </button>
        </form>
    </div>
</div>
