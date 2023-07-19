<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SendSMSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::where('user_id',Auth::id())
            ->select('name','id')
            ->withCount('contacts')
            ->paginate()
            ->map(fn($group) => [
                'id' => $group->id,
                'name' => $group->name,
                'size' => $group->contacts_count,
            ]);
        return inertia('SendSMS',compact('groups'));
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
        $request->validate([
            'recipients' => 'in:one,group',
            'group' => 'required_if:recipients,group|max:255',
            'phone' => 'required_if:recipients,one|max:255',
            'message' => 'required|string',
        ]);

        if($request->recipients == 'one')
        {
            // send sms to user
            $message = Message::create([
                'user_id' => Auth::id(),
                'recipient' => $request->phone,
                'content' => $request->message,
            ]);

            return redirect()->back()->withToast('Message sent');
        }
        else if($request->recipients == 'group')
        {
            // dispatch job to send sms
            dd('sending sms to many people');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
