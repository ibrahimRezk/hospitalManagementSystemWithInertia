<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use Translatable; // 2. To add translation methods
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
