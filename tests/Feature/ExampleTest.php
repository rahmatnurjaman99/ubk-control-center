<?php

declare(strict_types=1);

test('the application redirects to the admin panel', function () {
    $this->get('/')->assertRedirect('/admin');
});
