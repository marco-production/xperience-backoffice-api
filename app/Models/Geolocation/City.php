<?php

namespace App\Models\Geolocation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'iso2',
        'latitude',
        'longitude',
        'state',
        'state_code',
        'active'
    ];
}
