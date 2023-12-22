<?php

namespace App\Jobs;

use App\Models\Message;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    private string $userId;
    private mixed $messages;

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $messages)
    {
        $this->messages = $messages;

        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Message::insert([...$this->messages]);
    }

    public function failed($exception): void
    {
        info($exception->getMessage());
    }
}
