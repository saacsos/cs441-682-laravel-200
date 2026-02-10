<?php

it('can call api with limit 60 requests per minute', function () {
    $iteration = 100;
    $success = 0;
    for ($i = 0; $i < $iteration; $i++) {
        $response = $this->get('/api');
        if ($response->status() === 200) {
            $success++;
        }
    }
    $expected = 60;
    expect($success)->toBe($expected);
});
