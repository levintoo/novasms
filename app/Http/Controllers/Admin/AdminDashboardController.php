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

            'users' => User::query()

                ->withTrashed()

                ->count(),

            'active_users' => User::query()

                ->count(),

            'trashed_users' => User::query()

                ->onlyTrashed()

                ->count(),

            'groups' => Group::query()

                ->withTrashed()

                ->count(),

            'trashed_groups' => Group::query()

                ->onlyTrashed()

                ->count(),

            'contacts' => Contact::query()

                ->withTrashed()

                ->count(),

            'trashed_contacts' => Contact::query()

                ->onlyTrashed()

                ->count(),

            'messages' => Message::query()

                ->withTrashed()

                ->count(),

            'delivered_messages' => Message::query()

                ->whereNotNull('delivered_at')

                ->withTrashed()

                ->count(),

            'undelivered_messages' => Message::query()

                ->whereNull('delivered_at')

                ->withTrashed()

                ->count(),

        ]);

        return inertia('Admin/Dashboard',compact('stats'));
    }
}
