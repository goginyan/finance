<?php

namespace App\Models;

use App\Traits\PublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transaction;
use App\User;

class UserAccount extends Model
{
    use PublicId, SoftDeletes;

    const STATUS_ACTIVE = 1;

    protected $table = 'user_accounts';

    protected $guarded = ['id'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
