<?php

namespace App\Http\Controllers\User;

use Auth;
use Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specific resource.
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        return ResponseBuilder::success([
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        \App\User::update([
            'details->marketing' => 'sdgsfg',
//            'details->notifications' => $request->input('user.details.notifications'),
//            'details->language'      => $request->input('user.details.language'),
        ]);
//        \Log::info($request->user());
        \Log::info(\Auth::user());
        return ResponseBuilder::success([
            'user' => $request->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request->newPassword);
        $user->save();

        return ResponseBuilder::success([]);
    }
}
