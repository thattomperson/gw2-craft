<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUniqueIdentifier
{
//   public $keyType = 'string';
//   public $incrementing = false;


  public function initializeHasUniqueIdentifier()
  {
    $this->setKeyType('string');
    $this->setIncrementing(false);
  }

    public static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Str::uuid());
        });
    }
}