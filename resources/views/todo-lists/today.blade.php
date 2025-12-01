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
                class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors mb-2 bg-blue-50 dark:bg-blue-900/20"
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
                @include('todo-lists._list-item', ['list' => $list, 'isSelected' => false])
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
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4 md:p-6">
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
                <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Today</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, F j') }}</p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-6">
            <div class="max-w-3xl mx-auto">
                @if($todayItems->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg py-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-gray-900 dark:text-white font-medium">All clear for today!</p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">No tasks due today</p>
                    </div>
                @else
                    {{-- Group items by list --}}
                    @php
                        $groupedItems = $todayItems->groupBy('todo_list_id');
                    @endphp

                    @foreach($groupedItems as $listId => $items)
                        @php
                            $list = $items->first()->todoList;
                        @endphp
                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-2">
                                @if($list->color)
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $list->color }}"></div>
                                @endif
                                <a href="{{ route('todo-lists.show', $list) }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                    {{ $list->name }}
                                </a>
                            </div>
                            <div class="bg-white dark:bg-gray-800 rounded-lg">
                                @foreach($items as $item)
                                    @include('todo-lists.items._item', ['item' => $item, 'list' => $list])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
