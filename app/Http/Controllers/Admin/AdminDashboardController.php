<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $stats = collect([

            'users' => User::count(),

            'groups' => Group::count(),

            'contacts' => Contact::count(),

            'messages' => Message::count(),

        ]);

        return inertia('Admin/Dashboard',compact('stats'));
    }
}
