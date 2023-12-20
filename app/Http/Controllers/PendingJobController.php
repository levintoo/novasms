<?php

namespace App\Http\Controllers;

use App\Models\PendingJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;

class PendingJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        $jobs = PendingJob::query()

            ->where('user_id', $userId)

            ->select('name','batch_id')

            ->latest()

            ->paginate()

            ->through(fn($job) => [

            'name' => $job->name,

            'batch' => collect([

                Bus::findBatch($job->batch_id)->toArray(),

            ])->map(fn($batch) => [

                'id' => $batch['id'],

                'progress' => $batch['progress'],

                'finishedAt' => $batch['finishedAt'] ? $batch['finishedAt']->diffForHumans() : null,

                'failed' => (bool)$batch['cancelledAt'],

                'jobs' => (bool)$batch['totalJobs'],

            ]),

        ]);

        return inertia('PendingJobs', compact('jobs'));
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
