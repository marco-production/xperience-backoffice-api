<?php

namespace App\Models\Eticket;

use App\Models\User;
use App\Models\WeakEntities\Port;
use App\Models\WeakEntities\Motive;
use App\Models\WeakEntities\Airline;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_arrival',
        'application_id',
        'motive_id',
        'stop_over_in_countries',
        'airline_id',
        'origin_port_id',
        'origin_flight_number',
        'origin_flight_date',
        'embarkation_port_id',
        'disembarkation_port_id',
        'flight_date',
        'flight_number',
        'flight_confirmation_number',
        'qr_code',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_arrival' => 'boolean',
        'origin_port_id' => 'integer',
        'airline_id' => 'integer',
        'motive_id' => 'integer',
        'embarkation_port_id' => 'integer',
        'disembarkation_port_id' => 'integer',
        'user_id' => 'integer',
        'stop_over_in_countries' => 'boolean',
        'created_at' => 'datetime:Y-m-d',
        'origin_flight_date' => 'date',
        'flight_date' => 'date'
    ];

    /**
     * The users that belong to the Eticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The Traveler that belong to the Eticket
     */
    public function travelers()
    {
        return $this->belongsToMany(Traveler::class)->withTimestamps();
    }
    
    /**
     * Get the Airline that owns the Eticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    /**
     * Get the Motive that owns the Eticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function motive()
    {
        return $this->belongsTo(Motive::class);
    }

    /**
     * Get the Origin port that owns the Eticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id', 'id');
    }

    /**
     * Get the Embarkation port that owns the Eticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function embarkationPort()
    {
        return $this->belongsTo(Port::class, 'embarkation_port_id', 'id');
    }

    /**
     * Get the Disembarkation port that owns the Eticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disembarkationPort()
    {
        return $this->belongsTo(Port::class, 'disembarkation_port_id', 'id');
    }
}
