<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // protected $guarded=[];
    protected $fillable = ['invoice_status'];

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
}
