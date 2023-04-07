<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usershasequipments extends Model
{
    use HasFactory;

    protected $fillable = [
        'idUser',
        'idEquipment',
        'created_at',
        'updated_at',
    ];
}
