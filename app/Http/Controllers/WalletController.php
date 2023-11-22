<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
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

    public function top_up(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric'],
        ]);

        $user = User::findorfail(Auth::id());

            Transaction::create([
                'user_id' => Auth::id(),
                'amount' => rand(100,1000),
                'status' => '0',
                'type' => '0',
                'transaction_id' => Str::random(),
            ]);

        $user->balance = $user->balance + $request->amount / config('app.sms_rate');
        $user->save();
        toast('fake transaction finished','success');
//        return inertia_location('https://paystack.com/pay/3w3o-ziznp');
    }
}
