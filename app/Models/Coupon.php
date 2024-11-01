<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code','is_unique', 'discount', 'valid_from', 'valid_until'];

    protected $casts = [
        'is_unique' => 'boolean', // Define 'is_unique' como booleano para garantir a consistência
    ];

    /**
     * Converte o campo valid_from para Carbon se for string.
     */
    public function getValidFromAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    /**
     * Converte o campo valid_until para Carbon se for string.
     */
    public function getValidUntilAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
