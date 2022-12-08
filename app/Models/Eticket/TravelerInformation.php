<?php

namespace App\Models\Eticket;

use App\Models\Geolocation\Sector;
use App\Models\WeakEntities\Hotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelerInformation extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'day_of_staying',
        'particular_staying',
        'hotel_id',
        'sector_id',
        'street_address',
        'has_common_address',
        'is_task_return',
        'document_number',
        'phone_number',
        'air_ticket_number',
        'traveler_id',
        'eticket_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'traveler_informations';

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'day_of_staying' => 'integer',
        'particular_staying' => 'boolean',
        'hotel_id' => 'integer',
        'sector_id' => 'integer',
        'has_common_address' => 'boolean',
        'is_task_return' => 'boolean',
    ];

    /**
     * Get the Traveler that owns the Traveler Information
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function traveler()
    {
        return $this->belongsTo(Traveler::class);
    }

    /**
     * Get the Eticket that owns the Traveler Information
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eticket()
    {
        return $this->belongsTo(Eticket::class);
    }

    /**
     * Get the hotel that owns the Traveler Information
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the Sector that owns the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
}
