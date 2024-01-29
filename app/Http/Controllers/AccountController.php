<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Http\Requests\PayAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Http;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        request()->validate([

            'direction' => 'in:desc,asc',

            'field' => 'in:status,transacted_at,amount',

            'search' => 'max:255',
        ]);

        $userId = \Auth::id();

        $account = Account::query()

            ->select('balance')

            ->where('user_id', $userId)

            ->firstorfail();

        $query = Transaction::query();

        $query->where('user_id',$userId);

        if(request('field') == 'transacted_at') {
            $query->orderBy(
                'created_at',request('direction')
            );
        } else if(request('field') && request('direction')) {
            $query->orderBy(
                \request('field'),\request('direction')
            );
        } else{
            $query->latest();
        }

        $transactions = $query->paginate(10)->through(fn($transaction) => [
            'amount' => $transaction->amount,
            'status' => TransactionStatus::getValueName($transaction->status),
            'id' => $transaction->transaction_id,
            'transacted_at' => $transaction->created_at ? $transaction->created_at->format(config('app.date_time_format')) : null ,
        ]);

        $filters = request()->all([
            'field',
            'search',
            'direction',
        ]);

        return inertia('Account/Index', compact('account','transactions','filters'));
    }

    public function pay(PayAccountRequest $request)
    {
        $email = $request->user()->email;

        $validated = $request->validated();

        $amount = $validated['amount'] * 100;

        $url = "https://api.paystack.co/transaction/initialize";

        $fields = [
            'email' => $email,
            'amount' => $amount,
            'callback_url' => route('account.index')
        ];

        $headers = [
            'Authorization' => 'Bearer '.config('app.paystack_secret_key'),
        ];

        try {
            $response = Http::withHeaders($headers)->post($url, $fields);

            if ($response->successful()) {

                $data = $response->json();

                Transaction::create([
                    'user_id' => \Auth::id(),
                    'amount' => $amount / 100,
                    'transaction_id' => $data['data']['reference'],
                    'status' => TransactionStatus::PENDING ,
                ]);

                $authorization_url = $data['data']['authorization_url'];

                return inertia_location($authorization_url);

            } else {
                throw new Exception('Paystack API request failed with status code: ' . $response->status());
            }
        } catch (Exception $e) {
            toast('error', 'Something went wrong,please try again later');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
