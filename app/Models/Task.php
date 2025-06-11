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
}
