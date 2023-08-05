<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSMSRequest;
use App\Jobs\SendSMSJob;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Message::query();
        $query->where('user_id',Auth::id());
        $query->orderBy('created_at','DESC');
        $messages = $query->paginate()->through(fn($message) => [
            'id' => $message->id,
            'recipient' => $message->recipient,
            'content' => $message->content,
            'sent' => $message->created_at ? Carbon::parse($message->created_at)->diffForHumans() : null,
            'delivered' => $message->delivered_at ? Carbon::parse($message->delivered_at)->diffForHumans() : null,
        ]);
        return inertia('Sms/Index', compact('messages'));
    }

    public function send(StoreSMSRequest $request)
    {
        $validated = $request->validated();

        if($validated['recipients'] == 'one')
        {
            $message = Message::create([
                'user_id' => Auth::id(),
                'recipient' => $validated['phone'],
                'content' => $validated['message'],
            ]);

            return redirect()->back()->withToast('message sent');
        }

        else if($validated['recipients'] == 'group')
        {
            return $this->sendSMSToGroup($validated);
        }
    }
    /**
     * @param mixed $validated
     * @return mixed
     * @throws \Throwable
     */
    public function sendSMSToGroup(mixed $validated): mixed
    {
        $group_contacts = Contact::where('user_id', Auth::id())->where('group_id', $validated['group'])->count();

        if ($group_contacts <= 0) {
            return redirect()->back()->withToast('group does not have contacts');
        }

        $batch = Bus::batch([
            new SendSMSJob(Auth::id(), $validated['group'], $validated['message']),
        ])->dispatch();

        return redirect()->route('batch', $batch->id)->withToast('job dispatched');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
        return inertia('Sms/Send',compact('groups'));
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
    public function destroy($id)
    {
        $message = Message::where('id',$id)
            ->where('user_id',Auth::id())
            ->firstorfail();
        $message->delete();
        return redirect()->back()->withToast('message deleted');
    }
}
