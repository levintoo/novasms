<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactSmsRequest;
use App\Http\Requests\GroupSmsRequest;
use App\Jobs\SendSMSJob;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Message;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Validation\Rule;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        request()->validate([
            'direction' => 'in:desc,asc',
            'field' => 'in:recipient,content,sent',
            'search' => 'max:25',
            'status' => 'in:delivered,undelivered',
            'start_date' => 'string',
            'end_date' => 'string',
            'group' => [Rule::exists('groups','id')->where('user_id',Auth::id())],
        ]);

        $query = Message::query();

        $query->where('user_id',Auth::id());

        if(\request('start_date') && \request('end_date')){
            $query->whereBetween('created_at',[carbon::parse(\request('start_date')), carbon::parse(\request('end_date'))]);
        }

        if(request('group')) {
            $query->where('group_id',\request('group'));
        }

        if(request('status') === 'delivered') {
            $query->whereNotNull('delivered_at');
        }
        else if(request('status') === 'undelivered') {
            $query->whereNull('delivered_at');
        }

        if(request('search')) {
            $query->where(function ($query) {
                $query->where('recipient','LIKE','%'.request('search').'%')
                    ->orwhere('content','LIKE','%'.request('search').'%');
            });
        }

        if(request('field') == 'sent') {
            $query->orderBy('created_at',request('direction'));
        }
        else if(request('field') && request('direction')) {
            $query->orderBy(\request('field'),\request('direction'));
        }
        else{
            $query->orderBy('created_at','DESC');
        }

        $query->with('group:name,id');

        $messages = $query->paginate()->withQueryString()->through(fn($message) => [
            'id' => $message->id,
            'recipient' => $message->recipient,
            'content' => $message->content,
            'sent' => $message->created_at ? Carbon::parse($message->created_at)->diffForHumans() : null,
            'delivered' => $message->delivered_at ? Carbon::parse($message->delivered_at)->diffForHumans() : null,
            'group' => $message->group,
        ]);

        $groups = Group::where('user_id',Auth::id())
            ->select('name','id')
            ->orderBy('name','ASC')
            ->get()
            ->map(fn($group) => [
                'id' => $group->id,
                'name' => $group->name,
            ]);

        $filters = request()->all([
            'field',
            'search',
            'direction',
            'status',
            'group',
            'start_date',
            'end_date',
        ]);

        return inertia('Sms/Index', compact('messages','filters','groups'));
    }
    /**
     * Show the form for creating a new sms.
     */
    public function create()
    {
        $groups = Group::where('user_id',Auth::id())
            ->select('name','id')
            ->orderBy('name','ASC')
            ->withCount('contacts')
            ->get()
            ->map(fn($group) => [
                'id' => $group->id,
                'name' => $group->name,
                'size' => $group->contacts_count,
            ]);
        return inertia('Sms/Send',compact('groups'));
    }

    public function sendToContact(ContactSmsRequest $request)
    {
        $validated = $request->validated();

        $apiKey = config('app.sms_api_key');
        $senderId = config('app.sender_id');

        $client = new Client();
        try {
            $fullURL = "https://api.mobilesasa.com/v1/send/message";

            $body = [
                "senderID" => $senderId,
                "phone" => $validated['phone'],
                "message" => $validated['message'],
            ];

            $res = $client->post($fullURL, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => $body
            ]);

            $response = json_decode($res->getBody(), TRUE);
            // \Illuminate\Support\Facades\Log::info(json_encode($response));
             $smsCode = $response['messageID'][0];

            $message = Message::create([
                'user_id' => Auth::id(),
                'recipient' => $validated['phone'],
                'content' => $validated['message'],
                'delivered_at' => now(),
                'message_id' => $smsCode,
            ]);

        } catch (GuzzleException $e) {
        }

        info(json_encode($response ?? []));
        dd(json_encode($response ?? []));

        toast('message sent','success');
        return redirect()->route('messages');
    }
    public function sendToGroup(GroupSmsRequest $request)
    {
        $validated = $request->validated();

        $group_contacts = Contact::where('user_id', Auth::id())->where('group_id', $validated['group'])->count();

        if ($group_contacts <= 0) {
            toast('group does not have contacts','error');
            return redirect()->back();
        }

        $batch = Bus::batch([
            new SendSMSJob(Auth::id(), $validated['group'], $validated['message']),
        ])->dispatch();

        toast('job dispatched','');
        return redirect()->route('batch', $batch->id);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
        toast('message deleted','success');
        return redirect()->back();
    }
}
