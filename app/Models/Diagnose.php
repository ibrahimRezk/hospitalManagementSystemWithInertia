<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Diagnose extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    protected $with = (['doctor','patient']);

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id');
    }

    public function doctor(){
        return $this->belongsTo(User::class,'doctor_id');
    }

    public function patient(){
        return $this->belongsTo(User::class,'patient_id');
    }


}
