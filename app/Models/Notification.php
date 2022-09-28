<?php

namespace App\Models;

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
}
