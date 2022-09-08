<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Translatable;
    use HasFactory;
    public $translatedAttributes = ['name','description'];
    public $fillable= ['price','status'];
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


    public function groups() 
    {
        return $this->belongsToMany(Group::class,'services_groups')->withPivot('quantity');
    }

}
