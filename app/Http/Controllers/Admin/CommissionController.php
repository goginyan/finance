<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class CommissionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ResponseBuilder::success([
            'commissions' => Commission::get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Commission $commission
     * @return Response
     */
    public function show(Commission $commission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Commission $commission
     * @return Response
     */
    public function edit(Commission $commission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request    $request
     * @return Response
     */
    public function update(Request $request)
    {
        $commissions = $request->input('commissions');
        foreach ($commissions as $commission) {
            Commission::where('id', $commission['id'])
                ->update([
                    'is_active'     => $commission['is_active'],
                    'is_percentage' => $commission['is_percentage'],
                    'currency'      => $commission['currency'],
                    'amount'        => $commission['amount'],
                ]);
        }

        return ResponseBuilder::success([
            'commissions' => Commission::all(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Commission $commission
     * @return Response
     */
    public function destroy(Commission $commission)
    {
        //
    }
}
