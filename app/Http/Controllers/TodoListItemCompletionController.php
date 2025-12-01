<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\TodoListItem;

class TodoListItemCompletionController extends Controller
{
    /**
     * Toggle the completion status of a todo list item.
     */
    public function __invoke(TodoList $todoList, TodoListItem $item)
    {
        $item->update([
            'completed' => ! $item->completed,
        ]);

        return redirect()->route('todo-lists.show', $todoList);
    }
}
