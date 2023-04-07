<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetRep extends Model
{
    use HasFactory;

    protected $fillable = [
        'idTrainings_Exercise',
        'num_of_set',
        'num_of_reps',
        'weight',
    ];
}
