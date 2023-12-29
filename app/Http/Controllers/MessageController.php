<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Jobs\PrepareMessageJob;
use App\Models\Group;
use App\Models\Message;
use App\Models\PendingJob;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Validation\Rule;
use function request;

class MessageController extends Controller
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

            'group' => Rule::exists('groups','id')->where('user_id',Auth::id()),

        ]);

        $query = Message::query();

        $query->where('user_id', Auth::id());

        if (request('start_date') && request('end_date')) {
            $query->whereBetween(
                'created_at', [
                    Carbon::parse(request('start_date')),
                    Carbon::parse(request('end_date'))
                ]
            );
        }

        if (request('group')) {
            $query->where(
                'group_id', request('group')
            );
        }

        if (request('status') === 'delivered') {
            $query->whereNotNull(
                'delivered_at'
            );
        } else if(request('status') === 'undelivered') {
            $query->whereNull(
                'delivered_at'
            );
        }

        if(request('search')) {
            $query->where(function ($query) {
                $query->where(
                    'recipient', 'LIKE', '%' . request('search') . '%'
                )
                    ->orwhere(
                        'body', 'LIKE', '%' . request('search') . '%'
                    );
            });
        }

        if (request('field') == 'sent') {
            $query->orderBy(
                'created_at', request('direction')
            );
        } else if (request('field') && request('direction')) {
            $query->orderBy(
                request('field'), request('direction')
            );
        } else {
            $query->latest();
        }

        $query->with('group:name,id');

        $messages = $query->paginate()

            ->withQueryString()

            ->through(fn($message) => [

            'id' => $message->id,

            'recipient' => $message->recipient,

            'body' => $message->body,

            'sent' => $message->created_at ? $message->created_at?->diffForHumans() : null,

            'delivered' => $message->delivered_at ? $message->delivered_at->diffForHumans() : null,

            'group' => $message->group,

        ]);

        $groups = Group::query()

            ->where('user_id', Auth::id())

            ->select('name','id')

            ->orderBy('name','ASC')

            ->get()

            ->map(fn($group) => [
                'id' => $group->id,
                'name' => $group->name,
            ]);

        $filters = request()->all(['field', 'search', 'direction', 'status', 'group', 'start_date', 'end_date',]);

        return inertia('Message/Index', compact('messages','filters','groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::query()

            ->where('user_id', Auth::id())

            ->select('name','id')

            ->orderBy('name','ASC')

            ->withCount('contacts')

            ->get()

            ->map(fn($group) => [
                'id' => $group->id,
                'name' => $group->name,
                'contacts' => $group->contacts_count,
            ]);

        return inertia('Message/Create', compact('groups'));
    }


    public function send(StoreMessageRequest $request)
    {
        $validated = $request->validated();

        switch ($validated['recipient']) {

            case 'contact';

                $apiKey = config('app.sms_api_key');

                $senderId = config('app.sms_sender_id');

                $client = new Client();

                try {

                    $fullURL = "https://api.mobilesasa.com/v1/send/message";

                    $body = [

                        "senderID" => $senderId,

                        "phone" => $validated['phone'],

                        "message" => '[TEST]' . ' ' . $validated['message'],

                    ];

//                    $res = $client->post($fullURL, [
//
//                        'headers' => [
//
//                            'Accept' => 'application/json',
//
//                            'Authorization' => 'Bearer ' . $apiKey,
//
//                            'Content-Type' => 'application/json'
//
//                        ],
//
//                        'json' => $body
//
//                    ]);

//                    $response = json_decode($res->getBody(), TRUE);

                    $response = collect(array(
                        'status' => true,
                        'responseCode' => '0200',
                        'message' => 'Accepted',
                        'messageId' => fake()->uuid(),
                    ));

                    $smsCode = $response['messageId'];

                    $message = Message::create([

                        'user_id' => Auth::id(),

                        'recipient' => $validated['phone'],

                        'body' => $validated['message'],

                        'message_id' => $smsCode,

                    ]);

                    toast('success','message sent success');

                    return redirect()->route('message.index');

                } catch (GuzzleException $e) {

                    toast('error','something went wrong');

                    return redirect()->back();

                }

            break;

            case 'group';

                $groupsCount = Group::query()

                    ->where('id',$validated['group'])

                    ->select('id')

                    ->count();

                if($groupsCount === 0) {

                    toast('error','something went wrong');

                    return redirect()->back();
                }

                $userId = Auth::id();

                $batch = Bus::batch([

                    new PrepareMessageJob(
                        $userId,
                        $validated['group'],
                        $validated['message'],
                    ),

                ])->dispatch();

                PendingJob::create([
                    'user_id' => $userId,

                    'batch_id' => $batch->id,

                    'name' => 'preparing messages'
                ]);

                toast('info', 'job dispatched');

                return redirect()->route('message.index');

            break;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
