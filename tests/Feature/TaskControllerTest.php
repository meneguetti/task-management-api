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

test('fail when creating task with invalid data', function () {
    $task = Task::factory()->make();
    $task->status = 'invalid status';

    $response = $this->post('api/tasks', $task->toArray());
    $response->assertStatus(422);
    $response->assertJson();
});
