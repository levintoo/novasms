<?php

namespace App\Http\Controllers;

use App\Models\PendingJob;
use Illuminate\Http\Request;

class PendingJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = PendingJob::query()->where('user_id',\Auth::id())->paginate();

        return inertia('PendingJobs');
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
