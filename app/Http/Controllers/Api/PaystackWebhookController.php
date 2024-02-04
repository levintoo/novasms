<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaystackWebhookController extends Controller
{
    public function __invoke (Request $request) {
        $whiteListedIps = collect([
            '52.31.139.75',
            '52.49.173.169',
            '52.214.14.220',
        ]);

//        $ip = $request->ip();
//
//        if (!$whiteListedIps->contains($ip)) {
//            info('ip('.$ip.') is not whitelisted for paystack webhooks');
//            return;
//        }

        if (!$request->hasHeader('X-Paystack-Signature') || !$this->isSignatureValid($request)) {
            abort(400);
        }

        if ($request->event === 'charge.success') {
            $this->handleChargeSuccess($request);
        } else{
            info($request);
        }
    }

    private function handleChargeSuccess(Request $request) {
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

    private function isSignatureValid(Request $request): bool
    {
        $signature = $request->header('x-paystack-signature');

        $secretKey = config('app.paystack_secret_key');

        $payload = $request->getContent();

        $calculatedSignature = hash_hmac('sha512', $payload, $secretKey);

        return hash_equals($calculatedSignature, $signature);
    }
}
