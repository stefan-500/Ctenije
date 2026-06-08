<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'ime' => 'Test',
        'prezime' => 'User',
        'email' => 'test@example.com',
        'adresa' => 'Test Street 1, 11000 Beograd',
        'tel' => '+38161234567',
        'password' => 'N7!qR4#vT9@zL2',
        'password_confirmation' => 'N7!qR4#vT9@zL2',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice', absolute: false));
});
