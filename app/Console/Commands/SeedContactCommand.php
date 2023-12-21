<?php

namespace App\Console\Commands;

use App\Models\Contact;
use Illuminate\Console\Command;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\text;

class SeedContactCommand extends Command
{
    protected $signature = 'contacts:seed';

    protected $description = 'Command for seeding contacts to database';

    public function handle(): void
    {
        $items = text('items');

        $collection = collect([]);

        $items = $items ?: 100000;

        while ($items > 0) {

            $collection->add([

                'user_id' => 1,

                'group_id' => 1,

                'first_name' => fake()->firstName(),

                'last_name' => fake()->lastName(),

                'phone' => fake()->e164PhoneNumber(),

                'created_at' => now()->toDateTimeString(),
            ]);

            $items--;
        }

        $chunks = $collection->chunk(1000);

        foreach ($chunks as $chunk) {
            Contact::insert([...$chunk]);
        }

        outro('seeded success');
    }
}
