<?php

namespace App\Jobs;

use App\Imports\ContactImport;
use App\Mail\SendValidationErrors;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

            $errors = array();

            $max = 1000;

            foreach ($failures as $failure) {

                if ($max === 0) break;

                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                ];

                $max--;
            }

            $pdf = PDF::loadView('errors.report',compact('errors'));

            $file = 'import-errors-' . Str::uuid() . '.pdf';

            $filePath = 'errors/' . $file;

            $pdf->save($filePath ,'local');

            Mail::to(User::findOrFail($this->userId))->send(new SendValidationErrors($file));

            if(Storage::exists($filePath)){
                Storage::delete([$filePath]);
            }

        } finally {
            if(Storage::exists($this->filePath)){
                Storage::delete([$this->filePath]);
            }
        }
    }

    public function failed(): void
    {
        if(Storage::exists($this->filePath)){
            Storage::delete([$this->filePath]);
        }
    }
}
