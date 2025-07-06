<?php

use App\Livewire\Settings\Profile;
use App\Models\User;
use Livewire\Livewire;

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/settings/profile')->assertOk();
});

// test('profile information can be updated', function () {
//     $user = User::factory()->create();

//     $this->actingAs($user);

//     $response = Livewire::test(Profile::class)
//         ->set('name', 'Test User')
//         ->set('email', 'test@example.com')
//         ->call('updateProfileInformation');

//     $response->assertHasNoErrors();

//     $user->refresh();

//     expect($user->name)->toEqual('Test User');
//     expect($user->email)->toEqual('test@example.com');
//     expect($user->email_verified_at)->toBeNull();
// });

use App\Models\Setting;

test('profile information can be updated', function () {
    $user = User::factory()->create();

    // Buat setting bawaan untuk user tersebut
    Setting::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $response = Livewire::test(\App\Livewire\Settings\Profile::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('fullName', 'Test User Lengkap')
        ->set('phoneNumber', '08123456789')
        ->set('address', 'Jalan Testing')
        ->set('provinceId', 1)
        ->set('regencyId', 1)
        ->set('districtId', 1)
        ->set('villageId', 1)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
    expect($user->email_verified_at)->toBeNull();

    $setting = Setting::where('user_id', $user->id)->first();

    expect($setting)->not->toBeNull();
    expect($setting->full_name)->toEqual('Test User Lengkap');
    expect($setting->phone_number)->toEqual('08123456789');
    expect($setting->address)->toEqual('Jalan Testing');
});


test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Profile::class)
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('settings.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('settings.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
