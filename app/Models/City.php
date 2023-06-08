<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $lat
 * @property string $lon
 */
class City extends Model
{
    use HasFactory;

    public function history(): HasMany
    {
        return $this->hasMany(History::class);
    }
}
