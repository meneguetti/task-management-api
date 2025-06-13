<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            // DONE
            ['Create Migration', 'done', 'high', now()->subDays(4)],
            ['Implement RESTful API', 'done', 'high', now()->subDays(4)],
            ['Setup Pest 2', 'done', 'low', now()->subDays(3)],
            ['List Tasks', 'done', 'medium', now()->subDays(3)],
            ['Create Task', 'done', 'high', now()->subDays(2)],
            ['Edit Task', 'done', 'high', now()->subDays(1)],
            ['Delete Task', 'done', 'high', now()],
            ['Filter Tasks', 'done', 'medium', now()],

            // IN PROGRESS
            ['Laravel Echo', 'in_progress', 'medium', now()->addDays(5)],
            ['Websockets', 'in_progress', 'medium', now()->addDays(5)],
            ['Improve README', 'in_progress', 'medium', now()->addDays(3)],
            ['Provide Seeders', 'in_progress', 'low', now()],

            // 'TODO'
            ['Github Final Push', 'todo', 'high', now()->addDays(9)],
            ['Submit: Record Video', 'todo', 'high', now()->addDays(10)],
            ['Submit: Add GitHub URL', 'todo', 'high', now()->addDays(10)],
            ['Submit: Add Notes', 'todo', 'low', now()->addDays(10)],

            // BACKLOG
            ['Implement Vue.js', 'backlog', 'low', now()->addDays(9)],
            ['Use Pinia', 'backlog', 'low', now()->addDays(9)],
            ['Use Inertia.js', 'backlog', 'low', now()->addDays(9)],
            ['Documentation with OpenAPI/Swagger', 'backlog', 'medium', now()->addDays(9)],
        ];

        foreach ($tasks as $task) {
            Task::factory()->create([
                'title' => $task[0],
                'status' => $task[1],
                'priority' => $task[2],
                'due_date' => $task[3],
            ]);
        }
    }
}
