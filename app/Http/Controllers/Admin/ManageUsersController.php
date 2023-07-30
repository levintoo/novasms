<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManageUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = User::query();
        $query->orderBy('created_at','DESC');
        $query->withCount('groups');
        $query->withCount('contacts');
        $users = $query->paginate()->through(fn($user) => [
           'id' => $user->id,
           'name' => $user->name,
           'email' => $user->email,
            'balance' => $user->sms_balance,
            'contacts' => $user->contacts_count,
            'groups' => $user->groups_count,
            'joined' => $user->created_at ? Carbon::parse($user->created_at)->diffForHumans() : null ,
            'roles' => $user->getRoleNames(),
        ]);
        return inertia('Admin/Users/Index', compact('users'));
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
        $user = User::withCount('contacts')->withCount('groups')->findorfail($id);
        $roles = $user->getRoleNames();
        return inertia('Admin/Users/Edit',compact('user','roles'));
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
    public function destroy(string $id)
    {
        //
    }
}
