<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Astrotomic\Translatable\Translatable;
use App\Casts\PasswordCast;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use Translatable;
    use InteractsWithMedia;


    public $translatedAttributes =[ 'name', 'address'];
    public $with =['translations' ,'doctors_appointments'];



    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        // 'name',
        'email',
        'password',
        'email_verified_at',
        'phone',
        'section_id',
        'status',

        'birth_date',
        'gender'   , 
        'blood_group'   ,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => PasswordCast::class,
        'status' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];



    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class , 'section_id');

    }


    // public $with =['translations' , 'image' ,'section'];

    // public function image()
    // {
    //     return $this->morphOne(Image::class, 'imageable');
    // }

    
    public function doctors_appointments()
    {
        return $this->belongsToMany(Appointment::class,'doctors_appointments' , 'doctor_id'  , 'appointment_id');
    }


    public function doctor()
    {
        return $this->belongsTo(Invoice::class,'doctor_id');
    }

    public function service()
    {
        return $this->belongsTo(Invoice::class,'service_id'); 
    }

    public function invoices(){
        return $this->hasMany(Invoice::class,'patient_id');
    }
    public function payments(){
        return $this->hasMany(Payment::class,'patient_id');
    }
    public function receipts(){
        return $this->hasMany(Receipt::class,'patient_id');
    }


    public function scopeActive($builder)
    {
        return $builder->where('status', true);  // change it to active in database to be convenient
    }

    public function scopeInActive($builder)
    {
        return $builder->where('status', false);
    }


}
