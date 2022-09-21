<?php

namespace App\Models;

use App\Casts\LaboratoryCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Laboratory extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    protected $guarded=[];

    public $with =['doctor' , 'employee' , 'patient' , 'invoice'];

    protected $casts = [
        'status'=> LaboratoryCast::class
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class,'employee_id')
            ->withDefault(['name'=>'']);
            // ->withDefault(['name'=>'noEmployee']);
    }

    public function patient()
    {
        return $this->belongsTo(User::class,'patient_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }



    // public function images()
    // {
    //     return $this->morphMany(Image::class, 'imageable');
    // }
}
