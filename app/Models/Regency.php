<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $fillable = ['name', 'alt_name', 'latitude', 'longitude', 'jalan_kota', 'type_polygon', 'polygon'];
}