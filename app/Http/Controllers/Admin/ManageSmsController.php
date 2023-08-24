<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManageSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        request()->validate([
            'direction' => 'in:desc,asc',
            'field' => 'in:recipient,content,delivered,sent,group,user',
            'search' => 'max:255',
            'status' => 'in:delivered,undelivered',
            'trashed' => 'in:with,without,only',
            'start_date' => 'string',
            'end_date' => 'string',
            'user_id' => 'string','max:255',
            'group_id' => 'string','max:255'
        ]);

        $query = Message::query();

        if(request('field') == 'sent') {
            $query->orderBy('created_at',request('direction'));
        }
        else if(request('field') == 'user') {
            $query->orderBy('user_id',request('direction'));
        }
        else if(request('field') == 'group') {
            $query->orderBy('group_id',request('direction'));
        }
        else if(request('field') == 'delivered') {
            $query->orderBy('delivered_at',request('direction'));
        }
        else if(request('field') && request('direction')) {
            $query->orderBy(\request('field'),\request('direction'));
        }
        else{
            $query->orderBy('created_at','DESC');
        }

        if(request('trashed') == "with") {
            $query->withTrashed();
        }
        else if(request('trashed') == "only") {
            $query->onlyTrashed();
        }
        else {
            $query->withoutTrashed();
        }

        if(\request('start_date') && \request('end_date')){
            $query->whereBetween('created_at',[carbon::parse(\request('start_date')), carbon::parse(\request('end_date'))]);
        }

        if(\request('user_id')){
            $query->where('user_id', 'LIKE' , '%'.\request('user_id').'%' );
        }

        if(\request('group_id')){
            $query->where('group_id', 'LIKE' , '%'.\request('group_id').'%' );
        }

        if(request('search')) {
            $query->where(function ($query) {
                $query->where('recipient','LIKE','%'.request('search').'%')
                    ->orwhere('content','LIKE','%'.request('search').'%');
            });
        }

        if(request('status') === 'delivered') {
            $query->whereNotNull('delivered_at');
        }
        else if(request('status') === 'undelivered') {
            $query->whereNull('delivered_at');
        }

        $query->with('group:name,id');

        $query->with('user:email,name,id');

        $messages = $query->paginate()->withQueryString()->through(fn($message) => [
            'id' => $message->id,
            'recipient' => $message->recipient,
            'content' => $message->content,
            'sent' => $message->created_at ? Carbon::parse($message->created_at)->diffForHumans() : null,
            'delivered' => $message->delivered_at ? Carbon::parse($message->delivered_at)->diffForHumans() : null,
            'trashed' => !!$message->deleted_at,
            'group' => $message->group->name ?? null,
            'user' => [
                'name' => $message->user->name ?? null,
                'email' => $message->user->email ?? null,
            ],
        ]);

        $filters = request()->all([
            'search',
            'field',
            'direction',
            'status',
            'trashed',
            'start_date',
            'end_date',
            'user_id',
            'group_id',
        ]);
        return inertia('Admin/Messages/Index',compact('messages','filters'));
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
    public function destroy(Message $message)
    {
        $message->delete();
        toast('message removed','success');
    }

    /**
     * Restore the specified resource to storage.
     */
    public function restore(string $id)
    {
        $message = Message::withTrashed()->findorfail($id);
        $message->restore();
        toast('message restored','success');
    }
}
