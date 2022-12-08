<?php

namespace App\Models\Eticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WeakEntities\Currency;

class TravelerCustomsInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'exceeds_money_limit',
        'animals_or_food',
        'merch_with_tax_value',
        'is_values_owner',
        'sender_name',
        'sender_lastname',
        'receiver_name',
        'receiver_lastname',
        'receiver_relationship',
        'declared_origin_value',
        'worth_destiny',
        'amount',
        'currency_type_id',
        'value_of_merchandise',
        'merchandise_type_id',
        'declared_merchs',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'animals_or_food' => 'boolean',
        'exceeds_money_limit' => 'boolean',
        'merch_with_tax_value' => 'boolean',
        'is_values_owner' => 'boolean',
        'amount' => 'double',
        'value_of_merchandise' => 'double',
    ];

    /**
     * Get the Traveler that owns the Traveler Customs Information
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function traveler()
    {
        return $this->belongsTo(Traveler::class);
    }

    /**
     * Get the Eticket that owns the Traveler Customs Information
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eticket()
    {
        return $this->belongsTo(Eticket::class);
    }

    /**
     * Get the Currency Type that owns the Traveler Customs Information
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currencyType()
    {
        return $this->belongsTo(Currency::class, 'currency_type_id', 'id');
    }

    /**
     * Get the Merchandise Currency Type that owns the Traveler Customs Information
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchandiseCurrencyType()
    {
        return $this->belongsTo(Currency::class, 'merchandise_type_id', 'id');
    }
}
