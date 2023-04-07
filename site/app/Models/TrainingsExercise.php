<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingsExercise extends Model
{
    use HasFactory;
    protected $fillable = [
        'idTraining',
        'idExercise',
        'min',
    ];
}
