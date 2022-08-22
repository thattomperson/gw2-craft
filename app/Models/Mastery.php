<?php

namespace App\Models;

use App\Console\Support\Syncable;
use App\Models\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mastery extends Model implements Syncable
{
    use HasFactory;
    use HasUniqueIdentifier;

    protected $fillable = [
      'remote_id',
      'name',
      'requirement',
      'order',
      'background',
      'region',
      'created_at',
      'updated_at',
    ];

    public function levels()
    {
      return $this->hasMany(MasteryLevel::class);
    }

    public function formatFromApi($response): array
    {
      return (array) $response;
    }
}
