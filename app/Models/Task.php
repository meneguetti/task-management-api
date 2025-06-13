<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const STATUSES = [
        'BACKLOG' => 'backlog',
        'TODO' => 'todo',
        'IN_PROGRESS' => 'in_progress',
        'DONE' => 'done',
    ];

    const PRIORITIES = [
        'HIGH' => 'high',
        'MEDIUM' => 'medium',
        'LOW' => 'low',
    ];

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    public static function getTasksToBoard(?array $filter){
        return Task::orderByDesc('due_date')
            ->when($filter, function ($query, $filter) {
                if(isset($filter['title'])){
                    $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($filter['title']) . '%']);
                }
                if(isset($filter['status'])){
                    $query->whereIn('status', $filter['status']);
                }
                if(isset($filter['priority'])){
                    $query->whereIn('priority', $filter['priority']);
                }
                if(isset($filter['due_date_from'])){
                    $query->where('due_date', '>=', $filter['due_date_from']);
                }
                if(isset($filter['due_date_to'])){
                    $query->where('due_date', '<=', $filter['due_date_to']);
                }
            })
            ->paginate(3);
    }
}
