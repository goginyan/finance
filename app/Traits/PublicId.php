<?php

namespace App\Traits;

use App\Facades\HashId;

trait PublicId
{
    protected static function boot()
    {
        parent::boot();
        self::created(function ($model)
        {
            $model->update(['public_id' => HashId::encode($model->id)]);
        });
    }
}
