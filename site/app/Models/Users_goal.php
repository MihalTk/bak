<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'idUser',
        'idType',
        'ind-goal',
    ];
}
