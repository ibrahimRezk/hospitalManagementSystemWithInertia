<?php

namespace App\Models;

use App\Casts\TimstampCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['read_status','username','patient_id','message'];


    public function scopeCountNotification($query,$username)
    {
        $query->where('username',$username)->where('read_status',0);
    }

    protected $casts = [
        'created_at' => TimstampCast::class,
    ];

}
