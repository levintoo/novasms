<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManageGroupsController extends Controller
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
            'search' => ['max:25'],
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
        }
        else {
            $query->withoutTrashed();
        }

        if(request('field') == 'contacts') {
            $query->orderBy('contacts_count',request('direction'));
        }
        else if(request('field') == 'created') {
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

        $query->select('name','user_id','id','name','description','created_at','deleted_at');

        $query->withCount('contacts');

        $query->with('user:id,name,email');

        $groups = $query->paginate()->withQueryString()->through(fn($group) => [
            'id' => $group->id,
            'name' => $group->name,
            'description' => $group->description,
            'contacts' => $group->contacts_count,
            'trashed' => !!$group->deleted_at,
            'created' => $group->created_at ? carbon::parse($group->created_at)->diffForHumans() : null,
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
            'uid'
        ]);

        return inertia('Admin/Groups/Index',compact('groups','filters'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        DB::transaction(function () use ($group) {
            $group->contacts()->delete();
            $group->delete();
        });
        toast('group removed successfully','success');
    }

    /**
     * Restore the specified resource to storage.
     */
    public function restore($id)
    {
        $group = Group::query()->onlyTrashed()->findOrFail($id);
        DB::transaction(function () use ($group) {
            $group->contacts()->restore();
            $group->restore();
        });
        toast('group restored successfully','success');
    }
}
