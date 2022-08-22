<?php

namespace App\Models;

use App\Models\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasteryLevel extends Model
{
    use HasFactory;
    use HasUniqueIdentifier;

    protected $fillable = [
      'name',
      'description',
      'instruction',
      'icon',
      'point_cost',
      'exp_cost',
      'created_at',
      'updated_at',
    ];
}
