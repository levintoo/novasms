<?php

namespace App\Jobs;

use App\Models\PendingJob;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class PrepareMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;
    private int $groupId;
    private string $message;
    private string $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $groupId, $message)
    {
        $this->groupId = $groupId;

        $this->userId = $userId;

        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $batch = Bus::batch([])->dispatch();

        PendingJob::create([
            'user_id' => $this->userId,

            'batch_id' => $batch->id,

            'name' => 'send messages'
        ]);

        $start = microtime(true);

        DB::table('contacts')

            ->select('phone','first_name','last_name')

            ->orderBy('id')

            ->chunk(1000, function ($contacts) use($batch) {

            $replaceable = [
                '{{ first_name }}',

                '{{ last_name }}',

                '{{ phone }}',
            ];

            $messages = collect([]);

            $today = now()->toDateTimeString();

            foreach ($contacts as $contact) {

                $wordlist = [
                    $contact->first_name,

                    $contact->last_name,

                    $contact->phone,
                ];

                $messageContent = str_replace($replaceable, $wordlist, $this->message);

                $messages->add([
                    'user_id' => $this->userId,

                    'group_id' => $this->groupId,

                    'recipient' => $contact->phone,

                    'body' => $messageContent,

                    'created_at' => $today,
                ]);
            }

            $batch->add([
                new SendMessageJob($this->userId, $messages)
            ]);
        });
        $time = microtime(true) - $start;
        info("Query took " . $time . " seconds to execute.");
        \Laravel\Prompts\info("Query took " . $time . " seconds to execute.");
    }

    public function failed($exception): void
    {
        info($exception->getMessage());
    }
}
