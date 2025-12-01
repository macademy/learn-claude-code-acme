<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoList extends Model
{
    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * Get the items for the todo list.
     */
    public function todoListItems(): HasMany
    {
        return $this->hasMany(TodoListItem::class)->oldest();
    }

    /**
     * Scope a query to order by newest first.
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
