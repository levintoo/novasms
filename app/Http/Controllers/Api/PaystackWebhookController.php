<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PaystackWebhookController extends Controller
{
    public function __invoke (Request $request) {
        $whiteListedIps = collect([
            '52.31.139.75',
            '52.49.173.169',
            '52.214.14.220',
        ]);

//        $ip = $request->getClientIp();
//
//        if (!$whiteListedIps->contains($ip)) {
//            info('ip('.$ip.') is not whitelisted for paystack webhooks');
//            return;
//        }

        if (!$request->hasHeader('X-Paystack-Signature')) {
            abort(400);
        }

        info($request->header('X-Paystack-Signature'));

        $signature = $request->header('X-Paystack-Signature');

        $secretKey = config('app.paystack_secret_key');

        if($signature !== hash_hmac('sha512', $request->getContent(), $secretKey)) {
            info('failed hash test');
        }

        if ($request->event === 'charge.success') {
            $this->handleChargeSuccess($request);
        }

        info($request);
    }

    public function handleChargeSuccess($request) {
        $transaction = Transaction::where('transaction_id', $request['data']['reference'])->firstOrFail();

        $account = Account::where('user_id', $transaction->user_id)->firstorfail();

        $smsRate = config('app.sms_rate');

        $amount = $request['data']['amount'] / 100;

        $sms = $amount / $smsRate;

        $account->balance = $account->balance + $sms;

        $transaction->status = TransactionStatus::COMPLETED;

        $transaction->transacted_at = Carbon::parse($request['data']['paid_at'])->timezone(config('app.timezone'));

        DB::transaction(function () use ($request, $account, $transaction) {
            $account->save();
            $transaction->save();
        });
    }
}
