<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;
use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        request()->validate([
            'direction' => 'in:desc,asc',

            'field' => 'in:name,email,joined,verified',

            'trashed' => 'in:without,with,only',

            'search' => 'max:25'
        ]);

        $query = User::query();

        if(request('search')) {
            $query->where(function ($query) {
                $query->where('name','LIKE','%'.request('search').'%')
                    ->orwhere('email','LIKE','%'.request('search').'%');
            });
        }

        if(request('field') == 'contacts') {
            $query->orderBy(
                'contacts_count',request('direction'
            ));
        } else if(request('field') == 'groups') {
            $query->orderBy(
                'groups_count',request('direction')
            );
        } else if(request('field') == 'joined') {
            $query->orderBy(
                'created_at',request('direction')
            );
        } else if(request('field')) {
            $query->orderBy(
                request('field'),request('direction')
            );
        } else {
            $query->orderBy(
                'created_at','DESC'
            );
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

        $query->with('roles');

        $users = $query->paginate()->through(fn($user) => [

            'id' => $user->id,

            'name' => $user->name,

            'balance' => $user->balance,

            'email' => $user->email,

            'joined' => $user->created_at ? $user->created_at->format(config('app.date_time_format')) : null ,

            'verified' => $user->email_verified_at ? $user->email_verified_at->format(config('app.date_time_format')) : null ,

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
        $roles = Role::query()->select('name')->get();

        return inertia('Admin/User/Create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use($validated) {

            $user = User::create([
                ...$validated,
                'password' => Str::password(),
            ]);

            $user->assignRole($validated['role']);

            return $user;
        });

        toast('success','user created success');

        return redirect()->route('admin.user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $userId)
    {
        dd($userId);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $userId)
    {
        $roles = Role::select('name')->get();

        $user = User::select(['id','name','email'])

            ->where('id', $userId)

            ->with('roles')

            ->get()->map(fn($user) => [

            'id' => $user->id,

            'name' => $user->name,

            'email' => $user->email,

            'roles' => $user->roles->map(fn($role) => [
                'name' => $role->name,
            ]),

        ]);

        $user = $user[0];

        return inertia('Admin/User/Edit', compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update($validated);

        $user->syncRoles($validated['role']);

        toast('success', 'user updated success');

        return redirect()->route('admin.user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->id == Auth::id()) {

            toast('info', 'To delete your account head to the profile menu by clicking your name on the top right',null);

            return redirect()->back();
        }

        if($user->hasRole('admin') || $user->hasRole('super admin')) {
            if (!Auth::user()->can('manage admins')) {

                toast('error', 'cannot delete admins');

                return redirect()->back();
            }
        }

        DB::transaction(function () use ($user) {

            $user->contacts()->delete();

            $user->groups()->delete();

            $user->delete();

        });

        toast('success', 'user trashed successfully');

        return redirect()->back();
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findorfail($id);

        DB::transaction(function () use ($user) {

            $user->restore();

            $user->contacts()->onlyTrashed()->restore();

            $user->messages()->onlyTrashed()->restore();

            $user->groups()->onlyTrashed()->restore();
        });

        toast('success','user restored successfully');

        return redirect()->back();
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(User $user)
    {
        $status = Password::sendResetLink(
            ['email' => $user->email],
        );

        if ($status == Password::RESET_LINK_SENT) {
            toast('success', __($status));
        }

        toast('error', __($status));

        return redirect()->back();
    }
}
