<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $casts = [
        'gameTypes' => 'json',
        'flags' => 'json',
        'restrictions' => 'json',
        'details' => 'json',
    ];

    protected $fillable = [
        'remoteId',
        'name',
        'description',
        'type',
        'level',
        'rarity',
        'vendorValue',
        'defaultSkin',
        'gameTypes',
        'flags',
        'restrictions',
        'chatLink',
        'icon',
        'details',
    ];
}
