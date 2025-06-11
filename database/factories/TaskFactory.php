<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description'=> fake()->text(),
            'status' => Arr::random(Task::STATUSES),
            'priority' => Arr::random(Task::PRIORITIES),
            'due_date' => now()->addDays(rand(1,30))->format('Y-m-d'),
        ];
    }
}
