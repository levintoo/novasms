<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Requests\UploadContactRequest;
use App\Jobs\ContactImportjob;
use App\Models\Contact;
use App\Models\Group;
use App\Models\PendingJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        request()->validate([

            'direction' => 'in:desc,asc',

            'field' => 'in:first_name,last_name,phone,created,group',

            'search' => 'max:255',

            'group' => [Rule::exists('groups','id')->where('user_id',Auth::id())],

        ]);

        $query = Contact::query();

        if(request('search')) {
            $query->where(function ($query) {
                $query->where('first_name','LIKE','%'.request('search').'%')

                    ->orwhere('last_name','LIKE','%'.request('search').'%')

                    ->orwhere('phone','LIKE','%'.request('search').'%');
            });
        } else if(request('group')) {
            $query->where(
                'group_id',request('group')
            );
        }

        if(request('field') == 'created') {
            $query->orderBy(
                'created_at',request('direction')
            );
        } else if(request('field') && request('direction')) {
            $query->orderBy(
                \request('field'),\request('direction')
            );
        } else{
            $query->orderBy(
                'created_at','DESC'
            );
        }

        $query->where('user_id',Auth::id());

        $query->with('group:id,name');

        $query->select('id','phone','first_name','last_name','created_at','group_id');

        $contacts = $query->paginate()

            ->withQueryString()

            ->through(fn($contact) => [

                'id' => $contact->id,

                'phone' => $contact->phone,

                'first_name' => $contact->first_name,

                'last_name' => $contact->last_name,

                'created' => $contact->created_at ? $contact->created_at->diffForHumans() : null,

                'group' => $contact->group->name ?? null,
            ]);

        $filters = request()->all([

            'field',

            'search',

            'direction',

        ]);

        $groups = Group::query()

            ->where('user_id',Auth::id())

            ->select('name','id')

            ->orderBy('name','ASC')

            ->get()

            ->map(fn($group) => [

                'id' => $group->id,

                'name' => $group->name,

            ]);

        return inertia('Contact/Index', compact('groups','contacts','filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function inputCreate()
    {
        $groups = Group::query()

            ->where('user_id',Auth::id())

            ->select('name','id')

            ->orderBy('name','ASC')

            ->get();

        return inertia('Contact/Create', compact('groups'));
    }

    public function uploadCreate()
    {
        $groups = Group::query()

            ->where('user_id',Auth::id())

            ->select('name','id')

            ->orderBy('name','ASC')

            ->get();

        return inertia('Contact/Upload', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function InputStore(StoreContactRequest $request)
    {
        $validated = $request->validated();

        Contact::create([...$validated,'user_id' => Auth::id(), 'group_id' => $validated['group'] , ]);

        toast('success','contact created success');

        return redirect()->route('contact.index');
    }

    public function UploadStore(UploadContactRequest $request)
    {
        $validated = $request->validated();

        $filepath = $validated['file']->store('excel');

        $userId = Auth::id();

        $batch = Bus::batch([
            new ContactImportjob($validated['group'], $userId, $filepath),
        ])->dispatch();

        PendingJob::create([
            'user_id' => $userId,

            'batch_id' => $batch->id,

            'name' => 'uploading contacts'
        ]);

        toast('success','job dispatched');

        return redirect()->route('contact.index');
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
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.use Maatwebsite\Excel\Facades\Excel;
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        toast('success','contact removed success');

        return redirect()->route('contact.index');
    }
}
