<?php

test('list tasks', function () {
    $response = $this->get('api/tasks');

    $response->assertStatus(200);
});
