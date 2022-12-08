<?php

namespace App\Models\WeakEntities;

use App\Models\Eticket\Eticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motive extends Model
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
     * Get all of the Eticket for the Motive
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function etickets()
    {
        return $this->hasMany(Eticket::class);
    }
}
