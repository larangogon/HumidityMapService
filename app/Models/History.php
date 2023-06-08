<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $city_id
 * @property float $humidity
 */
class History extends Model
{
    use HasFactory;

    protected $fillable = ['humidity', 'city_id'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
