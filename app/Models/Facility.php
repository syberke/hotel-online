<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Model;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'facilities';

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'hours',
        'requires_booking',
        'category',
        'access_type',
        'hourly_capacity', // <--- Daftarkan di sini
    ];

    protected $casts = [
        'requires_booking' => 'boolean',
        'hourly_capacity'  => 'integer', // <--- Cast ke Integer
    ];
}