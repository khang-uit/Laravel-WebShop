<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'city',
        'state',
        'zipcode',
        'name',
        'phonenumber',
        'group',
        'url',
        'email',
        'latitude',
        'longitude'
    ];

    protected $primaryKey = 'id';
    protected $table = 'shops';
}