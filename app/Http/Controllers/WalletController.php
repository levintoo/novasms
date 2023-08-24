<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\ResponseFactory;

class WalletController extends Controller
{
    /**
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        $balance = \request()->user()->balance;
        return inertia('Wallet',compact('balance'));
    }

    /**
     * @param Request $request
     * @return void
     */
    public function top_up_with_mpesa(Request $request): void
    {
        $user = User::findorfail(Auth::id());
        $user->balance = $request->amount / config('app.sms_rate');
        $user->save();
        toast('transaction finished','success');
    }
}
