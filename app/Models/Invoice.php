<?php

namespace App\Models;

use App\Casts\InvoiceTypeCast;
use App\Casts\InvoiceTypeStatusCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Invoice extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    // protected $guarded=[];
    protected $fillable = ['invoice_status'];

    protected $casts = [
        'type'=> InvoiceTypeCast::class,
        'invoice_status'=> InvoiceTypeStatusCast::class
    ];
    protected $with =['group','service' , 'patient' ,'doctor' ,'section'];


    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id'); 
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id'); 
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function diagnose(){
        return $this->hasOne(Diagnose::class);
    }
}
