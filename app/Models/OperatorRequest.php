<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorRequest extends Model
{
    const TYPE_OPEN_BANK_ACCOUNT = 1;
    const TYPE_CLOSE_BANK_ACCOUNT = 2;

    protected $table = 'operator_requests';

    protected $guarded = ['id'];

    protected $fillable = [
        'type',
        'additional_info',
    ];
}
