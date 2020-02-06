<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commission;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\Models\UserAccount;

class AccountController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        return ResponseBuilder::success([
            'accounts' => UserAccount::with(['user', 'transactions'])->get()
        ]);
    }

    public function show($id){
        return ResponseBuilder::success([
            'account' => UserAccount::with(['user', 'transactions'])->find($id)
        ]);
    }

    public function update(Request $request){
        $account = UserAccount::find($request['account']['id']);
        $amount = $request['account']['amount'];
        \Log::info($amount);
        $is_incoming = $amount > 0 ? 1 : 0;
        $reference = $request['transaction']['reference'];

        $account->balance = $account->balance + $amount;
        $account->save();

        if ($amount != 0) {
            Transaction::create([
                'is_incoming' => $is_incoming,
                'reference'   => $reference,
                'account_id'  => $request['account']['id'],
                'user_id'     => $request['account']['user_id'],
                'amount'      => abs($amount),
                'commission'  => 0,
                'iban'        =>$is_incoming?(isset($request['transaction']['iban'])?$request['transaction']['iban']:null):null,
                'currency'    => $request['account']['currency'],
                'type'        => Transaction::TYPE_INNER,
                'status'      => Transaction::STATUS_PROCESSED,
            ]);
        }

        return ResponseBuilder::success([
            'success' => true,
        ]);
    }

    public function destroy(int $id)
    {
        UserAccount::destroy($id);

        return ResponseBuilder::success([
            'account' => UserAccount::withTrashed()->findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $account = collect($request->input('account'))
            ->only([
                'user_id',
                'iban',
                'name',
                'currency',
            ])
            ->toArray();

        $commission = Commission::where([
            ['currency', $account['currency']],
            ['type', Commission::COMMISSION_TYPE_ACC_OPEN],
            ['is_active', true]
        ])
            ->first();

        $commission = $commission != null ? $commission->amount : 0;

        $account['status'] = UserAccount::STATUS_ACTIVE;
        $account['balance'] = 0;
        $account['balance'] -= $commission;

        if (!empty($account)) {
            UserAccount::create($account);
        }

        return ResponseBuilder::success([
            'account' => $account
        ]);
    }
}
