<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Models\OperatorRequest;

class OperatorRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $operatorRequest = OperatorRequest::create($request);

        return ResponseBuilder::success([
            'operatorRequest' => $operatorRequest
        ]);
    }
}
