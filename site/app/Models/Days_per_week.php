<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Days_per_week extends Model
{
    use HasFactory;

    protected $fillable = [
        'days',
        'idReason',
        'idUser',
    ];
}
