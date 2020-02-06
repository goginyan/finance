<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\PublicId;
use Str;
use App\Models\Transaction;
use App\Models\UserAccount;

class User extends Authenticatable
{
    use Notifiable, HasRoles, PublicId, SoftDeletes;

    const USER_TYPE_PRIVATE = 1;
    const USER_TYPE_BUSINESS = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'pin',
        'public_id',
        'details',
        'user_type',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_admin',
        'is_operator',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'details' => 'array'
    ];

    public function accounts()
    {
        return $this->hasMany(UserAccount::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * Get the administrator flag for the user.
     * Magic accessor
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->hasRole('admin');
    }

    /**
     * Get the operator flag for the user.
     * Magic accessor
     * @return bool
     */
    public function getIsOperatorAttribute()
    {
        return $this->hasRole('operator');
    }

    public function getFullName()
    {
        return $this->name . ' ' . $this->last_name;
    }
}
