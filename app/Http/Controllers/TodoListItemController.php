<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\TodoListItem;
use Illuminate\Http\Request;

class TodoListItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TodoList $todoList)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $todoList->todoListItems()->create($validated);

        return redirect()->route('todo-lists.show', $todoList);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TodoList $todoList, TodoListItem $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $item->update($validated);

        return redirect()->route('todo-lists.show', $todoList);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TodoList $todoList, TodoListItem $item)
    {
        $item->delete();

        return redirect()->route('todo-lists.show', $todoList);
    }
}
