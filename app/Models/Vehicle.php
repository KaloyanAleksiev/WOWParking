<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'register_number', 'vehicle_type_id'
    ];

    /**
     * Vehicle Type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_type()
    {
        return $this->belongsTo('App\Models\VehicleType', 'vehicle_type_id');
    }

    /**
     * Current slot occupied by the vehicle
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function current_slot_occupied()
    {
        return $this->hasMany('App\Models\Slot', 'vehicle_id')->where('free', '=', '0');
    }
}
