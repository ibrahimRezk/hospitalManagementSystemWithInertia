<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PatientAccount extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class,'receipt_id');
    }


    public function payment()
    {
        return $this->belongsTo(Payment::class,'payment_id');
    }
}
