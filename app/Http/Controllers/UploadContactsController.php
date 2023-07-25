<?php

namespace App\Http\Controllers;

use App\Imports\ContactsImport;
use App\Jobs\ImportContactsJob;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UploadContactsController extends Controller
{
    public function index()
    {
        $groups = Group::where('user_id',Auth::id())->select('name','id')->get();
        return inertia('Contacts/UploadContacts',compact('groups'));
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
//        if(Storage::exists($filepath)){
//            Storage::delete([$filepath]);
//        }else{
//            dd('File does not exist.');
//        }
        return redirect()->route('contacts')->withToast('job dispatched');
    }
}
