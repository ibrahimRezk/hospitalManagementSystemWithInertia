<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Section extends Model implements HasMedia
{
    use Translatable; // 2. To add translation methods
    use InteractsWithMedia;

    protected $fillable =['name','description'];
    // 3. To define which attributes needs to be translated
    public $translatedAttributes = ['name','description'];
    use HasFactory;
    public $with =['translations'];


    public function doctors(): HasMany
    {
        return $this->hasMany(User::class ,'section_id');
    }
}
