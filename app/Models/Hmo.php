<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hmo extends Model
{
    use HasFactory;

    public function batch(): HasMany
    {
        return $this->hasMany(Hmo::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'hmo_code', 'code');
    }
}
