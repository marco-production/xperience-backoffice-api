<?php

namespace App\Models\WeakEntities;

use App\Models\Eticket\TravelerInformation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'name',
        'social_reason',
        'coordinates',
        'geo_code',
        'enabled'
    ];

        /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enabled' => 'boolean'
    ];

    /**
     * Get all of the Travelers for the Hotel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function travelersInformations()
    {
        return $this->hasMany(TravelerInformation::class);
    }
}
