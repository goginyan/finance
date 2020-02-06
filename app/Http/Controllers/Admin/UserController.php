<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserAccount;
use Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ResponseBuilder::success([
            'users' => User::with(['accounts', 'transactions'])->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = collect($request->input('user'))
            ->only([
                'name',
                'last_name',
                'email',
                'password',
                'pin',
                'user_type',
            ])
            ->toArray();
        $user['details'] = collect($request->input('user.details'))
            ->only([
                'company_name',
                'company_reg_number',
                'company_representative',
                'birth_date',
                'document_id',
                'phone_number',
                'address',
                'country',
                'zip_code',
                'marketing',
                'notifications',
                'language',
            ])
            ->toArray();

        if (!$user['password']) {
            unset($user['password']);
        } else {
            $user['password'] = Hash::make($user['password']);
        }

        if (!empty($user)) {
            $user = User::create($user);
        }

        return ResponseBuilder::success([
            'user' => $user
        ]);
    }

    public function show($id)
    {
        return ResponseBuilder::success([
            'user' => User::with('accounts')->findOrFail($id)
        ]);
    }

    public function update(Request $request, int $id)
    {
        if ($request->has('restore') && $request->restore == true) {
            $this->restoreUser($id);
        }

        $valuesForUpdate = collect($request->input('user'))
            ->only([
                'name',
                'last_name',
                'email',
                'pin',
                'user_type',
            ])
            ->toArray();
        $valuesForUpdate['details'] = collect($request->input('user.details'))
            ->only([
                'company_name',
                'company_reg_number',
                'company_representative',
                'birth_date',
                'document_id',
                'phone_number',
                'address',
                'country',
                'zip_code',
                'marketing',
                'notifications',
                'language'
            ])
            ->toArray();

        if (!empty($valuesForUpdate)) {
            User::where('id', $id)
                ->update($valuesForUpdate);
        }

        return ResponseBuilder::success([
            'user' => User::findOrFail($id)
        ]);
    }

    public function destroy(int $id)
    {
        User::destroy($id);
        return ResponseBuilder::success([
            'user' => User::withTrashed()->findOrFail($id),
        ]);
    }

    protected function restoreUser(int $id)
    {
        return User::withTrashed()->findOrFail($id)->restore();
    }

    public function accounts(int $id)
    {
        return ResponseBuilder::success([
            'accounts' => UserAccount::where('user_id', $id)->get()
        ]);
    }
}
