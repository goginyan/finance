<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\PublicId;
use App\Models\UserAccount;
use App\User;

class Transaction extends Model
{
    use PublicId, SoftDeletes;

    protected $table = 'transactions';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'account_id',
        'is_incoming',
        'amount',
        'commission',
        'type',
        'status',
        'reference',
        'payer',
        'beneficiary',
        'currency',
        'iban',
        'swift',
    ];

    public const STATUS_PROCESSING = 1;
    public const STATUS_PROCESSED = 2;
    public const STATUS_CANCELLED = 3;

    public const TYPE_INNER = 1;
    public const TYPE_STANDARD = 2;
    public const TYPE_EXPRESS = 3;

    public function userAccount()
    {
        return $this->hasOne(UserAccount::class, 'id', 'account_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
