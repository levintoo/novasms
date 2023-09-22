<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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

            Transaction::create([
                'user_id' => Auth::id(),
                'amount' => rand(100,1000),
                'status' => '0',
                'type' => '0',
                'transaction_id' => Str::random(),
            ]);

        $user->balance = $request->amount / config('app.sms_rate');
        $user->save();
        toast('transaction finished','success');
    }
}
