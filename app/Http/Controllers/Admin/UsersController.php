<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Group;
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

        $query->orderBy('created_at','DESC');

        $query->withCount('groups');

        $query->withCount('contacts');

        $users = $query->paginate()->through(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'contacts' => $user->contacts_count,
            'groups' => $user->groups_count,
            'balance' => $user->balance,
            'phone' => $user->phone,
            'email' => $user->email,
            'joined' => $user->created_at ? Carbon::parse($user->created_at)->diffForHumans() : null ,
            'roles' => $user->getRoleNames(),
            'trashed' => !!$user->deleted_at,
        ]);

        $filters = request()->all([
            'field',
            'search',
            'direction',
            'trashed'
        ]);

        return inertia('Admin/Users/Index', compact('users','filters'));
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

        if($validated['role'] != "admin" && $validated['role'] != "super admin") {
            $user->assignRole($validated['role']);
        } else if (Auth::user()->can('manage admins')) {
            $user->assignRole($validated['role']);
        } else {
            $user->assignRole('standard user');
        }

        toast('user created','success');
        return redirect()->route('admin.users');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::withTrashed()->with('contacts')->with('groups')->findorfail($id);

        return inertia('Admin/Users/Show',compact('user'));
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

        if($user->hasRole('admin') ||
            $user->hasRole('super admin') ||
            $validated['role'] === "admin" ||
            $validated['role'] === "super admin" ) {
            if (!Auth::user()->can('manage admins')) {
                toast('cannot manage admin roles','error');
                return redirect()->back();
            }
        }

        if($user->id != Auth::id()) {
            $user->syncRoles($validated['role']);
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);
        toast('user updated','success');
        return redirect()->route('admin.users');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if($id == Auth::id()) return redirect()->back()->withToast('To delete your account head to the profile menu by clicking your name on the top right');

        $user = User::findorfail($id);

        if($user->hasRole('admin') || $user->hasRole('super admin'))
            if(!Auth::user()->can('manage admins')) {
                toast('cannot delete admins','error');
                return redirect()->back();
            }

        $user->contacts()->delete();
        $user->groups()->delete();
        $user->delete();

        toast('user trashed successfully','success');
        return redirect()->back();
    }
    public function restore($id)
    {
        $user = User::onlyTrashed()->findorfail($id);

        $user->restore();

        $user->contacts()->onlyTrashed()->restore();

        $user->messages()->onlyTrashed()->restore();

        $user->groups()->onlyTrashed()->restore();

        toast('user restored successfully','success');
        return redirect()->back();
    }
}
