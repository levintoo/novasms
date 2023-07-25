<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        dd($validated);
    }
}
