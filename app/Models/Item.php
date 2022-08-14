<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $casts = [
        'game_types' => 'json',
        'flags' => 'json',
        'restrictions' => 'json',
        'details' => 'json',
    ];

    protected $fillable = [
        'remote_id',
        'name',
        'description',
        'type',
        'level',
        'rarity',
        'vendor_value',
        'default_skin',
        'game_types',
        'flags',
        'restrictions',
        'chat_link',
        'icon',
        'details',
    ];
}
