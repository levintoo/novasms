<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        request()->validate([
            'direction' => 'in:desc,asc',
            'field' => 'in:name,email,phone,joined,groups,balance,contacts',
            'trashed' => 'in:without,with,only',
            'search' => 'max:25'
        ]);

        $query = User::query();

        if(request('search')) {
            $query->where(function ($query) {
                $query->where('name','LIKE','%'.request('search').'%')
                    ->orwhere('email','LIKE','%'.request('search').'%')
                    ->orwhere('phone','LIKE','%'.request('search').'%');
            });
        }

        if(request('field') == 'contacts') {
            $query->orderBy('contacts_count',request('direction'));
        }
        else if(request('field') == 'groups') {
            $query->orderBy('groups_count',request('direction'));
        }
        else if(request('field') == 'joined') {
            $query->orderBy('created_at',request('direction'));
        }
        else if(request('field')) {
            $query->orderBy(request('field'),request('direction'));
        } else {
            $query->orderBy('created_at','DESC');
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

        $query->withCount('groups');

        $query->withCount('contacts');

        $query->with('roles');

        $users = $query->paginate()->through(fn($user) => [

            'id' => $user->id,

            'name' => $user->name,

            'contacts' => $user->contacts_count,

            'groups' => $user->groups_count,

            'balance' => $user->balance,

            'phone' => $user->phone,

            'email' => $user->email,

            'joined' => $user->created_at ? $user->created_at->diffForHumans() : null ,

            'roles' => $user->roles->map(fn($role) => [
                    'name' => $role->name,
            ]),

            'trashed' => !!$user->deleted_at,

        ]);

        $filters = request()->all(['field', 'search', 'direction', 'trashed']);

        return inertia('Admin/User/Index', compact('users','filters'));
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
