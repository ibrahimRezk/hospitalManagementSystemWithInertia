<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ambulance extends Model implements HasMedia
{
    use Translatable;
    use HasFactory;
    use InteractsWithMedia;
    public $translatedAttributes = ['driver_name','notes'];
    public $fillable= ['car_number','car_model','car_year_made','driver_license_number','driver_phone','is_available','car_type'];
    public $with =['translations'];


    public function scopeActive($builder)
    {
        return $builder->where('is_available', true);  // change it to active in database to be convenient
    }

    public function scopeInActive($builder)
    {
        return $builder->where('is_available', false);
    }

    protected $casts = [
        'is_available' => 'boolean',
    ];


}
