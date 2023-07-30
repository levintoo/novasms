<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory(10)->create();
         \App\Models\Group::factory(10)->create();
         \App\Models\Contact::factory(10)->create();
         \App\Models\Message::factory(10)->create();

         $this->call([
             RolesAndPermissionsSeeder::class,
         ]);

         $user = \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
             'phone' => fake()->e164PhoneNumber(),
         ]);

         $user->assignRole('admin');

    }
}
