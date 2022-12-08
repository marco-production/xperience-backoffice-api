<?php

namespace App\Models\Geolocation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
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
        'geo_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'macro_region_id',
        'region',
        'enabled'
    ];

    /**
     * Get all of the Sectors for the Municipality
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function macroRegion()
    {
        return $this->belongsTo(MacroRegion::class);
    }

    /**
     * Get all of the municipalities for the Province
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function municipalities()
    {
        return $this->hasMany(Municipality::class);
    }
}
