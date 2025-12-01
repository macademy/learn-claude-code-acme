@extends('layouts.app')

@section('content')
<div class="flex h-screen relative">
    <!-- Mobile Menu Overlay -->
    <div
        x-show="mobileMenuOpen"
        x-transition.opacity
        @click="mobileMenuOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
    ></div>

    <!-- Sidebar -->
    <div
        class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col transform transition-transform duration-300 md:translate-x-0"
        :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Lists</h1>
        </div>

        <div class="flex-1 overflow-y-auto p-3">
            {{-- Today Smart List --}}
            @php
                $todayCount = $todoLists->sum(fn($l) => $l->todoListItems->filter(fn($i) => !$i->completed && $i->due_date && $i->due_date->isToday())->count());
            @endphp
            <a
                href="{{ route('todo-lists.today') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors mb-2 {{ request()->routeIs('todo-lists.today') ? 'bg-blue-50 dark:bg-blue-900/20' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                @click="mobileMenuOpen = false"
            >
                <div class="w-6 h-6 rounded-full bg-yellow-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-base font-medium text-gray-900 dark:text-white">Today</p>
                </div>
                @if($todayCount > 0)
                    <span class="text-xs font-medium px-2 py-0.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-full">{{ $todayCount }}</span>
                @endif
            </a>

            <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

            @foreach($todoLists as $list)
                @include('todo-lists._list-item', ['list' => $list, 'isSelected' => $selectedList && $selectedList->id === $list->id])
            @endforeach
        </div>

        <div class="p-3 border-t border-gray-200 dark:border-gray-700">
            <form action="{{ route('todo-lists.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input
                    type="text"
                    name="name"
                    placeholder="New List"
                    required
                    class="flex-1 px-3 py-3 text-base bg-gray-100 dark:bg-gray-700 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 dark:text-white"
                >
                <button
                    type="submit"
                    class="px-4 py-3 text-base font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500"
                >
                    Add
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col bg-gray-50 dark:bg-gray-900">
        @if($selectedList)
            @php
                $totalItems = $selectedList->todoListItems->count();
                $completedItems = $selectedList->todoListItems->where('completed', true)->count();
                $activeItems = $selectedList->todoListItems->where('completed', false);
                $completedItemsList = $selectedList->todoListItems->where('completed', true);
            @endphp
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4 md:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <!-- Mobile Menu Button -->
                        <button
                            @click="mobileMenuOpen = true"
                            class="md:hidden p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        @if($selectedList->color)
                            <div class="w-6 h-6 md:w-8 md:h-8 rounded-full flex-shrink-0" style="background-color: {{ $selectedList->color }}"></div>
                        @endif
                        <div class="min-w-0">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $selectedList->name }}</h2>
                            @if($totalItems > 0)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $completedItems }} of {{ $totalItems }} completed</p>
                            @endif
                        </div>
                    </div>

                    {{-- Actions dropdown --}}
                    <div class="relative flex-shrink-0" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                            </svg>
                        </button>
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-1 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10"
                        >
                            <button
                                type="button"
                                onclick="document.getElementById('edit-list-form-{{ $selectedList->id }}').classList.toggle('hidden')"
                                @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                Edit list
                            </button>
                            <form action="{{ route('todo-lists.destroy', $selectedList) }}" method="POST" onsubmit="return confirm('Delete this list and all its items?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                                >
                                    Delete list
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Progress bar --}}
                @if($totalItems > 0)
                    <div class="mt-3 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div
                            class="h-full bg-blue-500 rounded-full transition-all duration-300"
                            style="width: {{ $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0 }}%"
                        ></div>
                    </div>
                @endif

                <form id="edit-list-form-{{ $selectedList->id }}" action="{{ route('todo-lists.update', $selectedList) }}" method="POST" class="hidden mt-4 flex flex-col md:flex-row gap-2">
                    @csrf
                    @method('PUT')
                    <input
                        type="text"
                        name="name"
                        value="{{ $selectedList->name }}"
                        required
                        class="flex-1 px-3 py-3 text-base bg-gray-100 dark:bg-gray-700 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 dark:text-white"
                    >
                    <input
                        type="color"
                        name="color"
                        value="{{ $selectedList->color ?? '#3b82f6' }}"
                        class="w-full md:w-16 h-12 rounded-lg cursor-pointer"
                    >
                    <button
                        type="submit"
                        class="px-4 py-3 text-base font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500"
                    >
                        Save
                    </button>
                </form>
            </div>

            <div class="flex-1 overflow-y-auto p-4 md:p-6">
                <div class="max-w-3xl mx-auto">
                    {{-- Quick add form at top --}}
                    <form action="{{ route('todo-lists.items.store', $selectedList) }}" method="POST" class="mb-4">
                        @csrf
                        @include('todo-lists.items._form')
                    </form>

                    {{-- Active items --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg">
                        @forelse($activeItems as $item)
                            @include('todo-lists.items._item', ['item' => $item, 'list' => $selectedList])
                        @empty
                            @if($completedItemsList->isEmpty())
                                <div class="py-8 text-center text-gray-500 dark:text-gray-400">
                                    <p>No tasks yet. Add one above!</p>
                                </div>
                            @endif
                        @endforelse
                    </div>

                    {{-- Completed items section --}}
                    @if($completedItemsList->isNotEmpty())
                        <div class="mt-4" x-data="{ showCompleted: false }">
                            <button
                                @click="showCompleted = !showCompleted"
                                class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 py-2"
                            >
                                <svg
                                    class="w-4 h-4 transition-transform"
                                    :class="showCompleted ? 'rotate-90' : ''"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Completed ({{ $completedItemsList->count() }})
                            </button>
                            <div x-show="showCompleted" x-collapse class="bg-white dark:bg-gray-800 rounded-lg mt-2 opacity-60">
                                @foreach($completedItemsList as $item)
                                    @include('todo-lists.items._item', ['item' => $item, 'list' => $selectedList])
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="flex-1 flex items-center justify-center">
                <p class="text-gray-500 dark:text-gray-400">Create a list to get started</p>
            </div>
        @endif
    </div>
</div>
@endsection
