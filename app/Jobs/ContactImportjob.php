<?php

namespace App\Jobs;

use App\Imports\ContactImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ContactImportjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, InteractsWithQueue, Batchable;

    private int $userId;
    private int $groupId;
    private string $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($groupId, $userId, $filePath)
    {
        $this->userId = $userId;

        $this->groupId = $groupId;

        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Excel::import(new ContactImport($this->groupId, $this->userId), $this->filePath);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
                info($failures);

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        } finally {
            if(Storage::exists($this->filePath)){

                Storage::delete([$this->filePath]);
            }
        }

        info('success message');
    }

    public function failed(\Exception $exception): void
    {
        info($exception->getMessage());

        if(Storage::exists($this->filePath)){

            Storage::delete([$this->filePath]);
        }
    }
}
