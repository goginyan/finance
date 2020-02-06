<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Transaction;
use App\Models\UserAccount;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\TransactionResource;
use Log;
use Str;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return ResponseBuilder::success([
            'transactions' => new TransactionResource($request->user()->transactions()->with('userAccount')->latest()->get())
        ]);

    }


    public function userTransaction($userId): Response {

        return ResponseBuilder::success([
            'transactions' => Transaction::whereHas('user',function($q) use ($userId){
                $q->where('id',$userId);
            })->with(['user'=>function($q) use ($userId){
                $q->where('id',$userId);
            }, 'userAccount'])->latest()->get()
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Validate user PIN using Request

        \Log::debug('=========================================');
        \Log::debug('Transaction store start');
        \Log::debug('Request: ');
        \Log::debug($request->all());

        $account = UserAccount::where('id', $request->account)->firstOrFail();

        \Log::debug('Account: ');
        \Log::debug($account);


        $commissionType = $request->paymentType == Transaction::TYPE_EXPRESS ?
            Commission::COMMISSION_TYPE_EXPRESS :
            Commission::COMMISSION_TYPE_TRANSACTION;

        \Log::debug('commission type: ');
        \Log::debug($commissionType);

        $commission = Commission::where([
            ['currency', $account->currency],
            ['type', $commissionType],
            ['is_active', true]
        ])
            ->first();

        \Log::debug('commission: ');
        \Log::debug($commission);

        $transaction = Transaction::create([
            'public_id'   => Str::random(10),
            'user_id'     => $request->user()->id,
            'account_id'  => $account->id,
            'amount'      => $request->amount,
            'currency'    => $account->currency,
            'type'        => $request->paymentType,
            'status'      => Transaction::STATUS_PROCESSED,
            'commission'  => $commission->amount,
            'iban'        => $request->iban,
            'swift'       => $request->swift,
            'reference'   => $request->reference,
            'payer'       => $request->user()->getFullName(),
            'beneficiary' => $request->beneficiary,
        ]);

        \Log::debug('transaction created: ');
        \Log::debug($transaction);
        $account->balance -= $request->amount;
        $account->balance -= $commission->amount;

        \Log::debug('account: ');
        \Log::debug($account);
        
        $account->save();
        return ResponseBuilder::success([
            'transactions' => $request->user()->transactions()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        try {
            $transaction = Transaction::with('user')
                ->where('public_id', '=', $request->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return ResponseBuilder::errorWithHttpCode(401, 401);
        }

        return ResponseBuilder::success([
            'transaction' => $transaction
        ]);
    }
}
