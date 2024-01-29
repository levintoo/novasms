<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        $user1 = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\Account::factory()->create([
            'user_id' => $user1->id,
        ]);

        $user1->assignRole(['super admin']);

        $user2 = \App\Models\User::factory()->create([
            'name' => 'Test User2',
            'email' => 'test@example2.com',
        ]);

        \App\Models\Account::factory()->create([
            'user_id' => $user2->id,
        ]);

        $user2->assignRole(['admin']);

        $user3 = \App\Models\User::factory()->create([
            'name' => 'Test User3',
            'email' => 'test@example3.com',
        ]);

        \App\Models\Account::factory()->create([
            'user_id' => $user3->id,
        ]);

        $user3->assignRole(['standard user']);

//        \App\Models\User::factory(10)->create();
        \App\Models\Group::factory(20)->create();
//        \App\Models\Contact::factory(10)->create();
//        \App\Models\Message::factory(10)->create();
    }
}
