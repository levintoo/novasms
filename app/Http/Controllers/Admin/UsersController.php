<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UsersController extends Controller
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
            'contacts' => $user->contacts_count,
            'groups' => $user->groups_count,
            'balance' => $user->sms_balance,
            'phone' => $user->phone,
            'email' => $user->email,
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
        $roles = collect([
            'admin',
            'super admin',
            'standard user',
        ]);
        return inertia('Admin/Users/Create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'phone' => 'required|string|max:255|unique:'.User::class,
            'role' => 'in:admin,standard user,super admin'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make(str::random()),
        ]);

        event(new Registered($user));

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users')->withToast('user created');
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

        $role = $user->getRoleNames();

        $all_roles = collect([
            'admin',
            'super admin',
            'standard user',
            ]);

        $user = collect([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);

        return inertia('Admin/Users/Edit',compact('user','role','all_roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email|max:255',
            'role' => 'in:admin,standard user,super admin'
        ]);

        $user = User::findorfail($id);

        if(!Auth::user()->hasRole('super admin') and !Auth::user()->hasRole($validated['role'])) {
            return redirect()->back()->withToast('action is unauthorized');
        }

        $user->syncRoles($validated['role']);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);
        return redirect()->route('admin.users')->withToast('user updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if($id == Auth::id()) return redirect()->back()->withToast('To delete your account head to the profile menu by clicking your name on the top right');

        $user = User::findorfail($id);
        $user->contacts()->delete();
        $user->groups()->delete();
        $user->delete();

        return redirect()->back()->withToast('user trashed successfully');
    }
}
