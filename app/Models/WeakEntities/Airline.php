<?php

namespace App\Models\WeakEntities;

use App\Models\Eticket\Eticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
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
        'code',
        'name',
        'origin_code',
        'observation',
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
     * Get all of the Eticket for the Airlane
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function etickets()
    {
        return $this->hasMany(Eticket::class);
    }
}
