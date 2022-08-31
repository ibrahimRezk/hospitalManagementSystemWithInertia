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


    protected $casts = [
        'status' => 'boolean',
    ];


    public function groups() 
    {
        return $this->belongsToMany(Group::class,'services_groups')->withPivot('quantity');
    }

}
