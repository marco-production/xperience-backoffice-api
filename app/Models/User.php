<?php

namespace App\Models;

use App\Models\Eticket\Eticket;
use App\Models\Geolocation\Country;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone_number',
        'avatar',
        'slug',
        'password',
        'country_id',
        'is_verified',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'country_id' => 'integer',
        'is_verified' => 'boolean',
        'active' => 'boolean',
        'deleted_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the country associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    /**
     * Get all of the etickets for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function etickets()
    {
        return $this->hasMany(Eticket::class);
    }

    /**
     * Get all of the travelers for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function travelers()
    {
        return $this->hasMany(Traveler::class);
    }
}
