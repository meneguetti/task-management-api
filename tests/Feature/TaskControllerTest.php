<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

test('list tasks', function () {
    $numOfTasks = 3;
    Task::factory()->count(3)->create();

    $response = $this->get('api/tasks');
    $response
        ->assertStatus(200)
        ->assertJson(
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
    $response
        ->assertStatus(201)
        ->assertJsonPath('data.id', 1);
});

test('updating task with invalid data fails', function () {
    $task = Task::factory()->create();
    $titleUpdated = 'Title updated'; 
    $task->title = $titleUpdated;
    unset($task->status);
    $response = $this->putJson("api/tasks/{$task->id}", $task->toArray());
    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('status');
});

test('update task', function () {
    $task = Task::factory()->create();
    $titleUpdated = 'Title updated'; 
    $task->title = $titleUpdated;
    $response = $this->putJson("api/tasks/{$task->id}", $task->toArray());
    $response
        ->assertStatus(200)
        ->assertJsonPath('data.title', $titleUpdated);
});
