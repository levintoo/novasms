<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\Http;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Account/Index');
    }

    public function pay(PayAccountRequest $request)
    {
        $email = $request->user()->email;

        $validated = $request->validated();

        $url = "https://api.paystack.co/transaction/initialize";

        $fields = [
            'email' => $email,
            'amount' => $validated['amount'] * 100,
            'callback_url' => route('account.index')
        ];

        $headers = [
            'Authorization' => 'Bearer '.config('app.paystack_secret_key'),
        ];

        try {
            $response = Http::withHeaders($headers)->post($url, $fields);

            if ($response->successful()) {

                $data = $response->json();

                info($data);

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
