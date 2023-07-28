<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
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
        return inertia('Messages', compact('messages'));
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
    public function update(Request $request, Message $message)
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
