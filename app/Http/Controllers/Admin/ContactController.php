<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        request()->validate([

            'direction' => ['in:desc,asc'],

            'field' => ['in:created,first_name,phone,last_name'],

            'trashed' => ['in:without,with,only'],

            'search' => ['max:255'],

            'uid' => ['string','max:255'],

        ]);

        $query = Contact::query();

        if(request('search')) {

            $query->where(function ($query) {

                $query->where('first_name','LIKE','%'.request('search').'%')

                    ->orwhere('last_name','LIKE','%'.request('search').'%')

                    ->orwhere('phone','LIKE','%'.request('search').'%');

            });
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

        if(request('field') == 'created') {
            $query->orderBy(
                'created_at',request('direction')
            );
        } else if(request('field')) {
            $query->orderBy(
                request('field'),request('direction')
            );
        } else {
            $query->orderBy(
                'created_at','DESC'
            );
        }

        if(request('uid')) {
            $query->where(
                'user_id',\request('uid')
            );
        }

        $query->with(['user' => function ($q) {
            $q->select('name','id','email')->withTrashed();
        }]);

        $query->with(['group' => function ($q) {
            $q->select('name','id')->withTrashed();
        }]);

        $contacts = $query->paginate()

            ->withQueryString()

            ->through(fn($contact) => [

            'id' => $contact->id,

            'first_name' => $contact->first_name,

            'last_name' => $contact->last_name,

            'trashed' => !!$contact->deleted_at,

            'created' => $contact->created_at ? $contact->created_at->format(config('app.date_time_format')) : null,

            'user' => [
                'name' => $contact->user->name ?? null,

                'email' => $contact->user->email ?? null,
            ],

            'group' => $contact->group->name ?? null,
        ]);

        $filters = request()->all([

            'search',

            'field',

            'direction',

            'trashed',

            'uid'

        ]);

        return inertia('Admin/Contact/Index',compact('filters','contacts'));
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
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        toast('success','contact removed');

    }

    /**
     * Restore the specified resource to storage.
     */
    public function restore(String $id)
    {

        $contact = Contact::query()

            ->onlyTrashed()

            ->findOrFail($id);

        $contact->restore();

        toast('success','contact restored');

    }
}
