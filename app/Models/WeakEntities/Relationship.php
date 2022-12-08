<?php

namespace App\Models\WeakEntities;

use App\Models\Eticket\Traveler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * Get all of the Travelers for the Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function travelers()
    {
        return $this->hasMany(Traveler::class);
    }
}
