<?php

namespace App\Models\WeakEntities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get all of the Eticket for the Disembarkation port
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ports()
    {
        return $this->hasMany(Port::class);
    }
}
