<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;



class UserTranslation extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name','address'];
    public $timestamps = false;
}
