<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        request()->validate([

            'direction' => ['in:desc,asc'],

            'field' => ['in:name,description,created,contacts'],

            'trashed' => ['in:without,with,only'],

            'search' => ['max:255'],

            'uid' => ['string','max:255'],

        ]);

        $query = Group::query();

        if(request('search')) {
            $query->where(function ($query) {
                $query->where('name','LIKE','%'.request('search').'%')

                    ->orwhere('description','LIKE','%'.request('search').'%')

                    ->orwhere('created_at','LIKE','%'.request('search').'%');

            });
        }

        if(request('trashed') == "with") {

            $query->withTrashed();

        }
        else if(request('trashed') == "only") {

            $query->onlyTrashed();

        } else {

            $query->withoutTrashed();

        }

        if(request('field') == 'contacts') {

            $query->orderBy('contacts_count',request('direction'));

        } else if(request('field') == 'created') {

            $query->orderBy('created_at',request('direction'));

        } else if(request('field')) {

            $query->orderBy(request('field'),request('direction'));

        } else {

            $query->orderBy('created_at','DESC');

        }

        if(request('uid')) {

            $query->where('user_id',\request('uid'));

        }

        $query->select('name','user_id','id','name','description','created_at','deleted_at');

        $query->withCount(['contacts' => function ($q) {
            $q->withTrashed();
        }]);

        $query->with('user:id,name,email');

        $groups = $query->paginate()

            ->withQueryString()

            ->through(fn($group) => [

            'id' => $group->id,

            'name' => $group->name,

            'description' => $group->description,

            'contacts' => $group->contacts_count,

            'trashed' => !!$group->deleted_at,

            'created' => $group->created_at ? $group->created_at->format(config('app.date_time_format')) : null,

            'user' => [

                'name' => $group->user->name ?? null,

                'email' => $group->user->email ?? null,

            ]
        ]);

        $filters = request()->all([

            'field',

            'search',

            'direction',

            'trashed',

            'uid',

        ]);

        return inertia('Admin/Group/Index', compact('filters','groups'));
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
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        //
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


    /**
     * Restore the specified resource to storage.
     */
    public function restore(String $group)
    {
        $group = Group::query()->onlyTrashed()->findOrFail($group);

        DB::transaction(function () use ($group) {

            $group->restore();

            $group->contacts()->onlyTrashed()->restore();

        });

        toast('success','group and its contents restored');

        return redirect()->back();

    }
}
