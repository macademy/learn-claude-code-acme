<?php

use App\Http\Controllers\TodoListController;
use App\Http\Controllers\TodoListItemCompletionController;
use App\Http\Controllers\TodoListItemController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/todo-lists');

Route::get('todo-lists/today', [TodoListController::class, 'today'])->name('todo-lists.today');
Route::resource('todo-lists', TodoListController::class)->except(['create', 'edit']);
Route::resource('todo-lists.items', TodoListItemController::class)->only(['store', 'update', 'destroy']);
Route::post('todo-lists/{todoList}/items/{item}/toggle', TodoListItemCompletionController::class)
    ->name('todo-lists.items.toggle');
