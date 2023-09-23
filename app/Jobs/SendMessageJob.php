<?php

namespace App\Jobs;

use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    private $message;
    private $recipient;
    private $user_id;
    private $group_id;

    /**
     * Create a new job instance.
     */
    public function __construct($user_id,$message,$recipient, $group_id)
    {
        $this->user_id = $user_id;
        $this->group_id = $group_id;
        $this->recipient = $recipient;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // send sms

        Message::create([
            'user_id' => $this->user_id,
            'recipient' => $this->recipient,
            'group_id' => $this->group_id,
            'content' => $this->message,
            'message_id' => \Illuminate\Support\Str::random(),
        ]);

        User::where('id', $this->user_id)->decrement('balance', 1);
    }

    public function failed(Exception $exception)
    {
        // Handle the failed job here
        // You can log the error, notify the user, or take other actions
        error_log('Job failed with message: ' . $exception->getMessage());

        // Example: Send a notification to the application administrators
        // Notification::route('mail', config('mail.admin_email'))
        //     ->notify(new JobFailedNotification($this, $exception));
    }
}
