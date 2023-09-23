<?php

namespace App\Http\Controllers;

use App\Models\JobStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;

class JobStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = JobStatus::query()
            ->orderBy('created_at','DESC')
            ->where('user_id',Auth::id())
            ->paginate()
            ->through(fn($job) => [
                'name' => $job->name,
                'info' => Bus::findBatch($job->batch_id)->toArray(),
            ]);
        return inertia('JobStatus', compact('jobs'));
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
    public function show(JobStatus $jobStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobStatus $jobStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobStatus $jobStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobStatus $jobStatus)
    {
        //
    }
}
