<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class PendingJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = PendingJob::query()

            ->with('user:id,name,email')

            ->paginate()

            ->through(fn($job) => [

                'name' => $job->name,

                'owner' => $job->user,

                'batch' => collect([

                    Bus::findBatch($job->batch_id)->toArray(),

                ])->map(fn($batch) => [

                    'id' => $batch['id'],

                    'progress' => $batch['progress'],

                    'finishedAt' => $batch['finishedAt'] ? $batch['finishedAt']->format(config('app.date_time_format')) : null,

                    'failed' => (bool)$batch['cancelledAt'],

                    'timeTaken' => $batch['finishedAt'] ? $batch['finishedAt']->diffInMinutes($batch['createdAt']) . ' minutes' : null,

                    'jobs' => $batch['totalJobs'],

                ])]);

        return inertia('Admin/PendingJobs/Index',compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PendingJob $pendingJob)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PendingJob $pendingJob)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PendingJob $pendingJob)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PendingJob $pendingJob)
    {
        //
    }
}
