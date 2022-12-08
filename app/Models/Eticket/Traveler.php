<?php

namespace App\Models\Eticket;

use App\Models\User;
use App\Models\WeakEntities\Occupation;
use App\Models\WeakEntities\Relationship;
use App\Models\WeakEntities\CivilStatus;
use App\Models\Geolocation\Country;
use App\Models\Geolocation\Sector;
use App\Models\Geolocation\City;
use App\Models\Eticket\PublicHealth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Traveler extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'lastname',
        'gender',
        'birthday',
        'birth_place_id',
        'nationality_id',
        'passport_number',
        'document_number',
        'email',
        'relationship_id',
        'occupation_id',
        'residential_country_id',
        'permanent_address', 
        'city_id',
        'zip_code',
        'residence_number',
        'street_address',
        'civil_status_id',
        'sector_id',
        'principal',
        'user_id'
    ];

    protected $hidden = [
        'pivot', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'relationship_id' => 'integer',
        'occupation_id' => 'integer',
        'city_id' => 'integer',
        'residential_country_id' => 'integer',
        'civil_status_id' => 'integer',
        'sector_id' => 'integer',
        'user_id' => 'integer',
        'zip_code' => 'integer',
        'principal' => 'boolean',
        'birthday' => 'date'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The Eticket that belong to the Traveler
     */
    public function etickets()
    {
        return $this->belongsToMany(Eticket::class)->withTimestamps();
    }

    /**
     * Get all of the Traveler Information (Eticket) for the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function travelerInformation()
    {
        return $this->hasMany(TravelerInformation::class);
    }

    /**
     * Get all of the Customs Information (Eticket) for the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customsInformation()
    {
        return $this->hasMany(TravelerCustomsInformation::class);
    }

    /**
     * Get all of the Public health (Eticket) for the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publicHealth()
    {
        return $this->hasMany(PublicHealth::class);
    }

    /**
     * Get the User that owns the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Occupation that owns the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    /**
     * Get the Relationship that owns the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relationship()
    {
        return $this->belongsTo(Relationship::class);
    }

    /**
     * Get the Civil Status that owns the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function civilStatus()
    {
        return $this->belongsTo(CivilStatus::class);
    }

    /**
     * Get the city that owns the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the Birth place that owns the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function birthPlace()
    {
        return $this->belongsTo(Country::class, 'birth_place_id', 'id');
    }

    /**
     * Get the Residential country that owns the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function residentialCountry()
    {
        return $this->belongsTo(Country::class, 'residential_country_id', 'id');
    }

    /**
     * Get the Nationality that owns the Traveler
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nationality_id', 'id');
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

    /**
     * Get age of Traveler
     *
     * @return int
     */
    public function getAgeAttribute() {
        return Carbon::parse($this->birthday)->age;
    }

    /**
     * Get fullname of Traveler
     *
     * @return int
     */
    public function getFullnameAttribute() {
        return $this->name.' '.$this->lastname;
    }
}
