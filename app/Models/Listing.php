<?php

namespace App\Models;

use App\Models\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;
    use HasUniqueIdentifier;

    protected $casts = [
        'buy' => 'json',
        'sell' => 'json',
    ];
}
