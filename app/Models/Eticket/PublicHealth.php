<?php

namespace App\Models\Eticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicHealth extends Model
{
    use HasFactory;

    protected $fillable = [
        'symptoms_date',
        'phone_number',
        'specification',
        'traveler_id',
        'eticket_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the Traveler that owns the Public Health
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function traveler()
    {
        return $this->belongsTo(Traveler::class);
    }

    /**
     * Get the Eticket that owns the Public Health
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eticket()
    {
        return $this->belongsTo(Eticket::class);
    }

    /**
     * The Symptoms that belong to the Public Health
     */
    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'public_health_symptoms', 'public_health_id', 'symptom_id')->withTimestamps();
    }
}
