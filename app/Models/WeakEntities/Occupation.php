<?php

namespace App\Models\WeakEntities;

use App\Models\Eticket\Traveler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'enabled'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean'
    ];

    /**
     * Get all of the Travelers for the Occupation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function travelers()
    {
        return $this->hasMany(Traveler::class);
    }
}
