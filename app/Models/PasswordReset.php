<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;
    public $primaryKey = 'phone';
    protected $fillable = [
        'phone',
        'token',
        'email',
    ];
}
