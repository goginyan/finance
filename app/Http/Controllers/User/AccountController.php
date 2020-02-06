<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OperatorRequest;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
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
            'accounts' => $request->user()->accounts()->get()
        ]);
    }

    public function store(Request $request)
    {
        $account = UserAccount::create($request);
        OperatorRequest::create([
            'type' => OperatorRequest::TYPE_OPEN_BANK_ACCOUNT
        ]);
        return ResponseBuilder::success([
            'account' => $account
        ]);
    }
}
