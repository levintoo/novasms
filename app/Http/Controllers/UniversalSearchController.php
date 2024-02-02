<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\Request;

class UniversalSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
           'search' => ['nullable','string','max:255']
        ]);

        $userId = \Auth::id();

        $dateTimeFormat = config('app.date_time_format');

        $pageSize = 3;

        $searchValue = $validated['search'];

        $messages = Message::query()

            ->select('recipient','body','created_at')

            ->where('user_id', $userId)

            ->where(function ($q) use($searchValue) {
                $q->where('recipient', 'LIKE' , '%'.$searchValue.'%')
                    ->orWhere('body', 'LIKE' , '%'.$searchValue.'%');
            })

            ->latest()

            ->simplePaginate($pageSize)->through(fn ($message) => [
                'body' => $message->body,
                'recipient' => $message->recipient,
                'sent' => $message->created_at ? $message->created_at->format($dateTimeFormat) : null ,
            ]);

        $contacts = Contact::query()

            ->select('first_name','last_name','id','phone','created_at')

            ->where('user_id', $userId)

            ->where(function ($q) use($searchValue) {
                $q->where('first_name', 'LIKE' , '%'.$searchValue.'%')
                    ->orWhere('last_name', 'LIKE' , '%'.$searchValue.'%')
                    ->orWhere('phone', 'LIKE' , '%'.$searchValue.'%');
            })

            ->latest()

            ->simplePaginate($pageSize)->through(fn ($message) => [
                'name' => $message->first_name . ' ' . $message->last_name,
                'phone' => $message->phone,
                'created' => $message->created_at ? $message->created_at->format($dateTimeFormat) : null ,
            ]);

        $groups = Group::query()

            ->select('name','created_at')

            ->where('user_id', $userId)

            ->where(function ($q) use($searchValue) {
                $q->where('name', 'LIKE' , '%'.$searchValue.'%')
                    ->orWhere('description', 'LIKE' , '%'.$searchValue.'%');
            })

            ->latest()

            ->simplePaginate($pageSize)->through(fn ($message) => [
                'name' => $message->name,
                'description' => $message->description,
                'created' => $message->created_at ? $message->created_at->format($dateTimeFormat) : null ,
            ]);

        return response([
            'messages' => $messages,
            'contacts' => $contacts,
            'groups' => $groups
        ]);
    }
}
