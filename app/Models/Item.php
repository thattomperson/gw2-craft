<?php

namespace App\Models;

use App\Console\Support\Syncable;
use App\Models\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;

class Item extends Model implements Syncable
{
    use HasFactory;
    use HasUniqueIdentifier;
    use Searchable;

    protected $keyType = 'string';
    public $incrementing = false;

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

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'output_item_id', 'remote_id');
    }

    public function ingredientIn()
    {
        return $this->hasManyThrough(Recipe::class, RecipeIngredient::class, 'remote_item_id', 'id', 'remote_id', 'recipe_id');
    }

    public function listing()
    {
        return $this->hasOne(Listing::class, 'remote_item_id', 'remote_id');
    }

    public function getLowestSellListingAttribute()
    {
        if ($this->listing) {
            return Arr::first($this->listing->sell);
        }

        return null;
    }

    public function getHighestBuyListingAttribute()
    {
        if ($this->listing) {
            return Arr::first($this->listing->buy);
        }

        return null;
    }

    public function toSearchableArray()
    {
      return [
        'name' => $this->name,
      ];
    }

    public function formatFromApi($response): array
    {
      return (array) $response;
    }

    public function setDefaultSkinAttribute($value)
    {
      $this->attributes['default_skin'] = $value ?? 0;
      return $this;
    }
}
