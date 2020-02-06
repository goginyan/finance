<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Commission extends Model
{
    const COMMISSION_TYPE_MONTHLY = 1;
    const COMMISSION_TYPE_TRANSACTION = 2;
    const COMMISSION_TYPE_ACC_OPEN = 3;
    const COMMISSION_TYPE_EXPRESS = 4;
    const COMMISSION_TYPE_INCOMING = 5;

    protected $fillable = [
        'id',
        'is_active',
        'is_percentage',
        'currency',
        'amount',
        'type',
    ];

}
