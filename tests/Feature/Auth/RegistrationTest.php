<?php


use App\Models\Account;
use App\Models\User;
use Spatie\Permission\Models\Role;

test('test registration screen can be rendered', function (): void {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('creates a user with role and account', function () {
    $fakeRole = Role::create(['name' => 'standard user']);

    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
    ];

    $user = DB::transaction(function () use ($userData, $fakeRole) {
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);

        $user->assignRole($fakeRole->name);

        Account::create(['user_id' => $user->id]);

        return $user;
    });

    expect($user->name)->toBe($userData['name'])
        ->and($user->email)->toBe($userData['email'])
        ->and(Hash::check($userData['password'], $user->password))->toBeTrue()
        ->and($user->hasRole($fakeRole->name))->toBeTrue()
        ->and(Account::where('user_id', $user->id)->exists())->toBeTrue();
});
