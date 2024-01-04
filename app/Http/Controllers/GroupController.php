<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

            $query->orderBy(
                'created_at',request('direction')
            );

        } else if(request('field') == 'size') {

            $query->orderBy(
                'contacts_count',request('direction')
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

        $groups = $query->paginate()

            ->withQueryString()

            ->through(fn($group) =>[

                'id' => $group->id,

                'name' => $group->name,

                'description' => $group->description,

                'created' => $group->created_at->diffForHumans(),

                'size' => $group->contacts_count

            ]);

        return inertia('Group/Index',compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Group/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        $validated = $request->validated();

        Group::create([...$validated, 'user_id' => Auth::id() ]);

        toast('success','group created');

        return redirect()->route('group.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        return inertia('Group/Edit', [

            'group' => collect([

                'id' => $group->id,

                'name' => $group->name,

                'description' => $group->description,

            ])
        ]);
    }

    /**
     * Update the specified resource in storage.,compact('groups')
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $validated = $request->validated();

        $group->update($validated);

        toast('success','group updated success');

        return redirect()->route('group.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        DB::transaction(function () use ($group) {

            $group->contacts()->delete();

            $group->delete();

        });

        toast('success','group and its contents deleted');

        return redirect()->back();
    }
}
