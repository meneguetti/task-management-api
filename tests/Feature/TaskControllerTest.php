<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

test('list tasks', function () {
    $numOfTasks = 3;
    $tasks = Task::factory()->count(3)->create();

    $response = $this->get('api/tasks');
    $response->assertStatus(200);
    $response->assertJson(
        fn (AssertableJson $json) => $json->has($numOfTasks)
    );
});

test('creating task with invalid data fails', function () {
    $task = Task::factory()->make();
    $task->status = 'invalid status';
    $response = $this->postJson('api/tasks', $task->toArray());
    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('status');
});

test('create task', function () {
    $task = Task::factory()->make();
    $response = $this->postJson('api/tasks', $task->toArray());
    $response->assertStatus(201);
    $response->assertJson(['id' => 1]);
});
