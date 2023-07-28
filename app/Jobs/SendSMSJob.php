<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Models\Message;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;
    private $group_id;
    private $message;
    private $user_id;

    /**
     * Create a new job instance.
     */
    public function __construct($user_id,$group_id,$message)
    {
        $this->group_id = $group_id;
        $this->user_id = $user_id;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $contacts = Contact::where('user_id',$this->user_id)
            ->select('first_name','last_name','phone')
            ->where('group_id',$this->group_id)
            ->inRandomOrder()
            ->limit(null)
            ->get();

        $replaceable = [
            '{{ first_name }}',
            '{{ last_name }}',
            '{{ phone }}',
        ];

        $chunkedContacts = $contacts->chunk(1000);

        $messages = collect();

        foreach ($chunkedContacts as $records) {
            foreach ($records as $contact) {

                $wordlist = [
                    $contact->first_name,
                    $contact->last_name,
                    $contact->phone,
                ];

                $message_content = str_replace($replaceable, $wordlist, $this->message);

                $messages->push([
                    'user_id' => $this->user_id,
                    'group_id' => $this->group_id,
                    'recipient' => $contact->phone,
                    'content' => $message_content
                ]);
            }
            Message::insert($messages->toArray());
            info('chunk inserted');
        }
    }

    public function failed(\Exception $exception)
    {
        info('failed...');
        info($exception->getMessage());
    }
}
