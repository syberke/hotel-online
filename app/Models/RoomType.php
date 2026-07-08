<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    // Tambahkan field baru ke dalam array $fillable agar bisa disimpan ke database
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'foto_url', 
        'max_capacity',
        'room_size', 
        'bed_configuration', 
        'view_perspective',
        'amenities'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}