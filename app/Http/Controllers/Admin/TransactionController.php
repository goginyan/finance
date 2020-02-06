<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commission;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return ResponseBuilder::success([
            'transactions' => Transaction::with(['user', 'userAccount'])->latest()->get()
        ]);
    }

    public function show($id)
    {
        return ResponseBuilder::success([
            'transaction' => Transaction::findOrFail($id)
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $transaction = collect($request->input('transaction'))
            ->only([
                'user_id',
                'account_id',
                'is_incoming',
                'is_accepted',
                'amount',
                'type',
                'status',
                'reference',
                'payer',
                'beneficiary',
                'iban',
                'swift',
            ])
            ->toArray();

        if ($transaction['is_incoming'] == '1') {
            $transaction['type'] = Transaction::TYPE_INNER;
        }
        $account = UserAccount::find($transaction['account_id']);
        $transaction['currency'] = $account->currency;

        $commissionType = $transaction['type'] == Transaction::TYPE_EXPRESS ?
            Commission::COMMISSION_TYPE_EXPRESS :
            Commission::COMMISSION_TYPE_TRANSACTION;

        $commissionType = $transaction['is_incoming'] ? Commission::COMMISSION_TYPE_INCOMING : $commissionType;

        $commission = Commission::where([
            ['currency', $transaction['currency']],
            ['type', $commissionType],
            ['is_active', true]
        ])
            ->first();

        $transaction['commission'] = $commission !== null ? $commission->amount : 0;

        if ($transaction['is_incoming'] == '1') {
            $account->balance += $transaction['amount'];
        } elseif ($transaction['is_incoming'] == '0') {
            $account->balance -= $transaction['amount'];
        }

        $account->balance -= $commission->amount;

        if (!empty($transaction)) {
            Transaction::create($transaction);
            $account->save();
        }
        if ($transaction['type'] == Transaction::TYPE_INNER && $transaction['is_incoming'] == '0') {
            $account = UserAccount::where('iban', $transaction['iban'])->first();
            $account->balance += $transaction['amount'];
            $account->save();
        }
        return ResponseBuilder::success([
            'transaction' => $transaction
        ]);
    }

    public function update(Request $request, int $id)
    {
        $transaction = collect($request->input('transaction'))
            ->only([
                'user_id',
                'account_id',
                'is_incoming',
                'is_accepted',
                'amount',
                'commission',
                'type',
                'status',
                'reference',
                'payer',
                'iban',
                'swift',
                'beneficiary',
            ])
            ->toArray();

        if (!empty($transaction)) {
            Transaction::where('id', $id)
                ->update($transaction);
        }

        return ResponseBuilder::success([
            'transaction' => Transaction::findOrFail($id)
        ]);
    }

    public function bulkAccept(Request $request){
        Transaction::whereIn('id', $request['ids'])->update([
            'is_accepted' => 1,
        ]);

        return ResponseBuilder::success();
    }
}
