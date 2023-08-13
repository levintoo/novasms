<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
            'search' => 'max:25',
            'group' => [Rule::exists('groups','id')->where('user_id',Auth::id())],
        ]);

        $query = Contact::query();

        if(request('search')) {
            $query->where(function ($query) {
                $query->where('first_name','LIKE','%'.request('search').'%')
                    ->orwhere('last_name','LIKE','%'.request('search').'%')
                    ->orwhere('phone','LIKE','%'.request('search').'%');
            });
        }

        else if(request('group')) {
            $query->where('group_id',request('group'));
        }

        if(request('field') == 'created') {
            $query->orderBy('created_at',request('direction'));
        }
        else if(request('field') && request('direction')) {
            $query->orderBy(\request('field'),\request('direction'));
        }
        else{
            $query->orderBy('created_at','DESC');
        }

        $query->where('user_id',Auth::id());

        $query->with('group:id,name');

        $query->select('id','phone','first_name','last_name','created_at','group_id');

        $contacts_count = $query->count();

        $contacts = $query->paginate()
            ->withQueryString()
            ->through(fn($contact) => [
                'id' => $contact->id,
                'phone' => $contact->phone,
                'first_name' => $contact->first_name,
                'last_name' => $contact->last_name,
                'created' => $contact->created_at ? Carbon::parse($contact->created_at)->diffForHumans() : null,
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
            ->withCount('contacts')
            ->get()->map(fn($group) => [
            'id' => $group->id,
            'name' => $group->name,
            'size' => $group->contacts_count,
        ]);

        return inertia('Contacts/Index', compact('groups','contacts','contacts_count','filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::query()
            ->where('user_id',Auth::id())
            ->select('name','id')
            ->orderBy('name','ASC')
            ->get();
        return inertia('Contacts/Create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group' => ['required',Rule::exists('groups','id')->where('user_id',Auth::id())],
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'phone' => 'required|max:255',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'group_id' => $validated['group'],
            ...$validated
        ]);

        toast('contact created','success');

        return redirect()->route('contacts');
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
    public function edit($id)
    {
        $contact = Contact::where('id',$id)
            ->select('id','first_name','last_name','phone','group_id')
            ->where('user_id',Auth::id())
            ->firstorfail();

        $contact = [
          'first_name' => $contact->first_name,
          'last_name' => $contact->last_name,
          'phone' => $contact->phone,
          'group' => $contact->group_id,
          'id' => $contact->id,
        ];

        $groups = Group::where('user_id',Auth::id())
            ->select('name','id')
            ->get();

        return inertia('Contacts/Edit', compact('id','contact','groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'group' => ['required',Rule::exists('groups','id')->where('user_id',Auth::id())],
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'phone' => 'required|max:255',
        ]);

        $contact = Contact::where('id',$id)
            ->where('user_id',Auth::id())
            ->firstorfail();

        $contact->group_id = $validated['group'];
        $contact->first_name = $validated['first_name'];
        $contact->last_name = $validated['last_name'];
        $contact->phone = $validated['phone'];
        $contact->save();

        toast('contact updated','success');

        return redirect()->route('contacts');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contact = Contact::query()
            ->where('id',$id)
            ->where('user_id',Auth::id())
            ->firstorfail();
        $contact->delete();

        toast('contact removed','success');

        return redirect()->back();
    }
}
