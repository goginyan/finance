<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class DetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return ResponseBuilder::success([
            'details' => Auth::user()->details()->firstOrFail()['details'],
        ]);
    }

    public function store(Request $request)
    {
        $userDetails = $request->user()->details()->firstOrFail();
        $details = $userDetails->details;
        $details['marketing'] = $request->marketing;
        $details['notifications'] = $request->notifications;
        $details['language'] = $request->language;
        $userDetails->details = $details;
        $userDetails->save();

        return ResponseBuilder::success([
            'details' => $details
        ]);
    }
}
