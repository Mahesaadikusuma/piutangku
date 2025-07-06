<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

// test('users can authenticate using the login screen', function () {
//     $user = User::factory()->create();

//     $response = Livewire::test(Login::class)
//         ->set('email', $user->email)
//         ->set('password', 'password')
//         ->call('login');

//     $response
//         ->assertHasNoErrors()
//         ->assertRedirect(route('dashboard', absolute: false));

//     $this->assertAuthenticated();
// });

test('customer users are redirected to dashboard-customer', function () {
    $user = User::factory()->create();
    $user->assignRole('customer'); // pakai spatie role

    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard-customer', absolute: false));

    $this->assertAuthenticatedAs();
});

test('admin or company users are redirected to dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('admin'); // atau 'company'

    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs();
});


test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'wrong-password')
        ->call('login');

    $response->assertHasErrors('email');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');

    $this->assertGuest();
});
