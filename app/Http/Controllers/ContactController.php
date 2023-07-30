<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Contact::query();
        $query->where('user_id',Auth::id());
        $query->orderBy('created_at','DESC');
        $query->with('group:id,name');
        $query->select('id','phone','first_name','last_name','created_at','group_id');
        $contacts = $query->paginate()
            ->withQueryString()
            ->through(fn($contact) => [
                'id' => $contact->id,
                'phone' => $contact->phone,
                'first_name' => $contact->first_name,
                'last_name' => $contact->last_name,
                'created' => $contact->created_at ? Carbon::parse($contact->created_at)->diffForHumans() : null,
                'group' => $contact->group->name ?? null,
        ]);
        return inertia('Contacts/Index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::where('user_id',Auth::id())->select('name','id')->get();
        return inertia('Contacts/Create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group' => ['required',Rule::exists('groups','id')->where('user_id',Auth::id())],
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|max:255',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'group_id' => $validated['group'],
            ...$validated
        ]);

        return redirect()->route('contacts')->withToast('contact created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contact = Contact::where('id',$id)
            ->select('id','first_name','last_name','phone','group_id')
            ->where('user_id',Auth::id())
            ->firstorfail();

        $contact = [
          'first_name' => $contact->first_name,
          'last_name' => $contact->last_name,
          'phone' => $contact->phone,
          'group' => $contact->group_id,
          'id' => $contact->id,
        ];

        $groups = Group::where('user_id',Auth::id())
            ->select('name','id')
            ->get();

        return inertia('Contacts/Edit', compact('id','contact','groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'group' => ['required',Rule::exists('groups','id')->where('user_id',Auth::id())],
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|max:255',
        ]);

        $contact = Contact::where('id',$id)
            ->where('user_id',Auth::id())
            ->firstorfail();

        $contact->group_id = $validated['group'];
        $contact->first_name = $validated['first_name'];
        $contact->last_name = $validated['last_name'];
        $contact->phone = $validated['phone'];
        $contact->save();

        return redirect()->route('contacts')->withToast('contact updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contact = Contact::where('id',$id)
            ->where('user_id',Auth::id())
            ->firstorfail();
        $contact->delete();
        return redirect()->back()->withToast('contact deleted');
    }
}
