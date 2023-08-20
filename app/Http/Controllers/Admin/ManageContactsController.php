<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageContactsController extends Controller
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
            'search' => ['max:25'],
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
            $query->orderBy('created_at',request('direction'));
        }
        else if(request('field')) {
            $query->orderBy(request('field'),request('direction'));
        } else {
            $query->orderBy('created_at','DESC');
        }

        if(request('uid')) {
            $query->where('user_id',\request('uid'));
        }

        $query->with('user:id,name,email');

        $query->with('group:name,id');

        $contacts = $query->paginate()->withQueryString()->through(fn($contact) => [
            'id' => $contact->id,
            'first_name' => $contact->first_name,
            'last_name' => $contact->last_name,
            'trashed' => !!$contact->deleted_at,
            'created' => $contact->created_at ? carbon::parse($contact->created_at)->diffForHumans() : null,
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

        return inertia('Admin/Contacts/Index',compact('filters','contacts'));
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

    public function destroy(Contact $contact)
    {
        $contact->delete();
        toast('contact removed','success');
    }

    /**
     * Restore the specified resource to storage.
     */
    public function restore($id)
    {
        Contact::query()->onlyTrashed()->findOrFail($id)->restore();
        toast('contact restored','success');
    }
}
