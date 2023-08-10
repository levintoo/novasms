<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        request()->validate([
            'direction' => 'in:desc,asc',
            'field' => 'in:name,description,size,created',
            'search' => 'max:25',
        ]);

        $query = Group::query();

        $query->where('user_id',Auth::id());

        $query->select('id','name','description','created_at');

        $query->withCount('contacts');

        if(request('search')) {
            $query->where(function ($query) {
                $query->where('name','LIKE','%'.request('search').'%')
                    ->orwhere('description','LIKE','%'.request('search').'%');
            });
        }

        if(request('field') == 'created') {
            $query->orderBy('created_at',request('direction'));
        }
        else if(request('field') == 'size') {
            $query->orderBy('contacts_count',request('direction'));
        }
        else if(request('field') && request('direction')) {
            $query->orderBy(\request('field'),\request('direction'));
        }
        else{
            $query->orderBy('created_at','DESC');
        }

        $groups_count = $query->count();

        $groups = $query->paginate()
            ->withQueryString()
            ->through(fn($group) =>[
                'id' => $group->id,
                'name' => $group->name,
                'description' => $group->description,
                'created' => Carbon::create($group->created_at)->diffForHumans(),
                'size' => $group->contacts_count
        ]);

        return inertia('Groups/Index',compact('groups','groups_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Groups/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
           'name' => ['required','string','max:100'],
           'description' => ['max:2550'],
        ]);

        $group = Group::create([
            'user_id' => Auth::id(),
            ...$validated
        ]);

        return redirect()->route('groups')->withToast('group created');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $group = Group::where('user_id',Auth::id())
            ->select('name','description','created_at','updated_at')
            ->withCount('contacts')
            ->findorfail($id);

        $group = collect([
            'name' => $group->name,
            'description' => $group->description,
            'size' => $group->contacts_count,
            'created' => Carbon::parse($group->created_at)->format('F j,Y h:i a'),
            'updated' =>  Carbon::parse($group->updated_at)->format('F j,Y H:i a'),
        ]);

        $query = Contact::query();

        $query->where('user_id',Auth::id());

        $query->orderBy('created_at','DESC');

        $query->where('group_id',$id);

        $query->select('id','phone','first_name','last_name','created_at');

        $contacts = $query->paginate()
            ->withQueryString()
            ->through(fn($contact) => [
                'id' => $contact->id,
                'phone' => $contact->phone,
                'first_name' => $contact->first_name,
                'last_name' => $contact->last_name,
                'created' => $contact->created_at ? carbon::parse($contact->created_at)->diffForHumans() : null,
            ]);

        return inertia('Groups/View', compact('group','contacts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $group = Group::where('user_id',Auth::id())
            ->where('id',$id)
            ->select('id','name','description')
            ->firstorfail();
        return inertia('Groups/Edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:100'],
            'description' => ['max:2550'],
        ]);

        $group = Group::where('user_id',Auth::id())
            ->where('id',$id)
            ->select('id','name','description')
            ->firstorfail();

        $group->name = $validated['name'];
        $group->description = $validated['description'];
        $group->save();

        return redirect()->route('groups')->withToast('group updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $group = Group::where('id',$id)
            ->where('user_id',Auth::id())
            ->firstorfail();
        $group->contacts()->delete();
        $group->delete();
        return redirect()->back()->withToast('group and its contents deleted');
    }
}
