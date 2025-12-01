<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\TodoListItem;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todoLists = TodoList::with('todoListItems')->newest()->get();
        $selectedList = $todoLists->first();

        return view('todo-lists.index', compact('todoLists', 'selectedList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $todoList = TodoList::create($validated);

        return redirect()->route('todo-lists.show', $todoList);
    }

    /**
     * Display the specified resource.
     */
    public function show(TodoList $todoList)
    {
        $todoLists = TodoList::with('todoListItems')->newest()->get();
        $selectedList = $todoList->load('todoListItems');

        return view('todo-lists.index', compact('todoLists', 'selectedList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TodoList $todoList)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $todoList->update($validated);

        return redirect()->route('todo-lists.show', $todoList);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TodoList $todoList)
    {
        $todoList->delete();

        return redirect()->route('todo-lists.index');
    }

    /**
     * Display items due today across all lists.
     */
    public function today()
    {
        $todoLists = TodoList::with('todoListItems')->newest()->get();

        $todayItems = TodoListItem::with('todoList')
            ->whereDate('due_date', today())
            ->where('completed', false)
            ->oldest()
            ->get();

        return view('todo-lists.today', compact('todoLists', 'todayItems'));
    }
}
