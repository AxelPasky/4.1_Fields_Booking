<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price_per_hour',
        'is_available', // Aggiungi questa riga
    ];

    /**
     * Get the bookings for the field.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
