<?php

namespace App\Models;

use App\Console\Support\Syncable;
use App\Models\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model implements Syncable
{
    use HasFactory;
    use HasUniqueIdentifier;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'flags' => 'json',
        'disciplines' => 'json',
    ];

    protected $fillable = [
        'remote_id',
        'type',
        'output_item_id',
        'output_item_count',
        'time_to_craft_ms',
        'disciplines',
        'min_rating',
        'flags',
        'chat_link',
        'updated_at',
        'created_at',
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Item::class, 'recipe_ingredient', 'recipe_id', 'remote_item_id', 'id', 'remote_id')
            ->using(RecipeIngredient::class)
            ->withPivot('count');
    }

    public function recipeIngredients()
    {
      return $this->hasMany(RecipeIngredient::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'output_item_id', 'remote_id');
    }

    public function formatFromApi($response): array
    {
      return (array) $response;
    }

}
