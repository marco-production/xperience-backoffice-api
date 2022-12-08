<?php

namespace App\Models\WeakEntities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'code',
        'transportation_id',
        'place',
        'name',
        'dominican_port',
        'enabled'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transportation_id' => 'integer',
        'dominican_port' => 'boolean',
        'enabled' => 'boolean'
    ];

    /**
     * Get all of the Eticket for the Origin port
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function originPortEtickets()
    {
        return $this->hasMany(Eticket::class, 'origin_port_id', 'id');
    }

    /**
     * Get all of the Eticket for the Embarkation port
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function embarkationEtickets()
    {
        return $this->hasMany(Eticket::class, 'embarkation_port_id', 'id');
    }

    /**
     * Get all of the Eticket for the Disembarkation port
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disembarkationEtickets()
    {
        return $this->hasMany(Eticket::class, 'disembarkation_port_id', 'id');
    }

    /**
     * Get the transportation that owns the Port
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transportation()
    {
        return $this->belongsTo(Transportation::class);
    }
}
