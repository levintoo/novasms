<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Models\PendingJob;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

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
        $contacts = Contact::query()

            ->where('user_id', $this->userId)

            ->select('first_name', 'last_name', 'phone')

            ->where('group_id', $this->groupId)

            ->inRandomOrder()

            ->get();

        $replaceable = [
            '{{ first_name }}',

            '{{ last_name }}',

            '{{ phone }}',
        ];

        $chunkedContacts = $contacts->chunk(169);

        $batch = Bus::batch([])->dispatch();

        PendingJob::create([
            'user_id' => $this->userId,

            'batch_id' => $batch->id,

            'name' => 'send messages'
        ]);

        foreach ($chunkedContacts as $records) {

            $messages = collect([]);

            foreach ($records as $contact) {

                $wordlist = [
                    $contact->first_name,

                    $contact->last_name,

                    $contact->phone,
                ];

                $messageContent = str_replace($replaceable, $wordlist, $this->message);

                $today = now()->toDateTimeString();

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

        }
    }

    public function failed($exception): void
    {
        info($exception->getMessage());
    }
}
