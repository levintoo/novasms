<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Bus;

class BatchProgressController extends Controller
{
    public function index($batchId)
    {
        $batch = Bus::findBatch($batchId);
        $batchinfo = $batch->toArray();
        return inertia('BatchProgress',compact('batchinfo'));
    }
    public function getProgress($batchid)
    {
        return redirect()->route('batch',$batchid);
    }
}
