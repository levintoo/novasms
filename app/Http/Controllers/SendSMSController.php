<?php

namespace App\Http\Controllers;

use App\Models\Contact;
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

        if($request['recipients'] == 'one') {

            // send sms

            $message = Message::create([
                'user_id' => Auth::id(),
                'recipient' => $request['phone'],
                'content' => $request['message'],
            ]);

            return redirect()
                ->back()
                ->withToast('message sent');
        }

        else if($request['recipients'] == 'group') {

            $group_contacts = Contact::where('user_id',Auth::id())
                ->where('group_id',$request['group'])
                ->count();

            if($group_contacts <= 0) {
                return redirect()
                    ->back()
                    ->withToast('group does not have contacts');
            }

            // send sms to groups
            $preview_replaceable = [
                '{{ first_name }}',
                '{{ last_name }}',
                '{{ phone }}',
            ];

            $contact = Contact::where('user_id',Auth::id())
                ->select('first_name','last_name','phone')
                ->where('group_id',$request['group'])
                ->inRandomOrder()
                ->firstorfail();

            $preview_wordlist = [
                $contact->first_name,
                $contact->last_name,
                $contact->phone,
            ];

            $preview_message = str_replace($preview_replaceable, $preview_wordlist, $request['message']);

            return redirect()
                ->back()
                ->withToast($preview_message);
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
