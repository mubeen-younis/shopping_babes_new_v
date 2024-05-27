<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MethodShipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'regular_post',
        'express_post',
        'rider',
    ];
}
