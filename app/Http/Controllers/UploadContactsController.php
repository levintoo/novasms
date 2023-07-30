<?php

namespace App\Http\Controllers;

use App\Jobs\ImportContactsJob;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Validation\Rule;
class UploadContactsController extends Controller
{
    public function index()
    {
        $groups = Group::where('user_id',Auth::id())->select('name','id')->get();
        return inertia('Contacts/Upload',compact('groups'));
    }
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'group' => ['required',Rule::exists('groups','id')->where('user_id',Auth::id())],
            'file'=>'required|mimes:xlsx,odt'
        ]);

        $filepath = $validated['file']->store('excel');

        $batch = Bus::batch([
            new ImportContactsJob($validated['group'],Auth::id(),$filepath),
        ])->dispatch();

        return redirect()->route('batch', $batch->id)->withToast('job dispatched');
    }
}
