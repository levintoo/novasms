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
        $query = Group::query();
        $query->where('user_id',Auth::id());
        $query->orderBy('created_at','DESC');
        $query->select('id','name','description','created_at');
        $query->withCount('contacts');
        $groups = $query->paginate()
            ->through(fn($group)=>[
            'id' => $group->id,
            'name' => $group->name,
            'description' => $group->description,
            'created' => Carbon::create($group->created_at)->diffForHumans(),
                'size' => $group->contacts_count
        ]);
        return inertia('Groups/Groups',compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Groups/CreateGroup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
           'name' => ['required','string','max:255'],
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
            'created' => Carbon::parse($group->created_at)->format('F j,Y H:i A T'),
            'updated' =>  Carbon::parse($group->updated_at)->format('F j,Y H:i A T'),
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

        return inertia('Groups/ViewGroup', compact('group','contacts'));
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
        return inertia('Groups/EditGroup', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
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
