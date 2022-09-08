<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use Translatable;
    use HasFactory;
    public $translatedAttributes = ['name','notes'];
    public $fillable= ['insurance_code','discount_percentage','Company_rate','status'];
    public $with =['translations'];


    public function scopeActive($builder)
    {
        return $builder->where('status', true);  // change it to active in database to be convenient
    }

    public function scopeInActive($builder)
    {
        return $builder->where('status', false);
    }

    protected $casts = [
        'status' => 'boolean',
    ];
}
