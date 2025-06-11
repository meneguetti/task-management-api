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
