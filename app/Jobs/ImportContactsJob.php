<?php

namespace App\Jobs;

use App\Imports\ContactsImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, InteractsWithQueue, Batchable;

    private mixed $user_id;
    private mixed $group_id;
    private mixed $filepath;

    /**
     * Create a new job instance.
     */
    public function __construct($group_id,$user_id,$filepath)
    {
        $this->user_id = $user_id;
        $this->group_id = $group_id;
        $this->filepath = $filepath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::import(new ContactsImport($this->group_id,$this->user_id), $this->filepath);
        if(Storage::exists($this->filepath)){
            Storage::delete([$this->filepath]);
        }
    }

    public function failed(\Exception $exception)
    {
        info($exception->getMessage());
        if(Storage::exists($this->filepath)){
            Storage::delete([$this->filepath]);
        }
    }
}
