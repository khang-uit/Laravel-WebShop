<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'review',
        'name',
        'email',
        'rating',
        'product_id',
    ];

    protected $primaryKey = 'id';
    protected $table = 'reviews';
}
