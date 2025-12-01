<div class="flex flex-col sm:flex-row gap-2">
    <div class="flex-1 flex items-center gap-2 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent">
        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <input
            type="text"
            name="title"
            value="{{ $item->title ?? '' }}"
            placeholder="{{ isset($item) ? 'Task title' : 'Add a task...' }}"
            required
            class="flex-1 py-1 text-base bg-transparent border-0 focus:ring-0 dark:text-white placeholder-gray-400"
        >
    </div>
    <input
        type="date"
        name="due_date"
        value="{{ isset($item) && $item->due_date ? $item->due_date->format('Y-m-d') : '' }}"
        class="w-full sm:w-auto px-3 py-3 text-base bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white dark:[color-scheme:dark]"
    >
    <button
        type="submit"
        class="px-5 py-3 text-base font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    >
        {{ isset($item) ? 'Save' : 'Add' }}
    </button>
</div>
