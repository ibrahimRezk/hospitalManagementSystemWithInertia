<?php

namespace App\Http\Requests\Admin;

use App\Models\AmbulanceTranslation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AmbulancesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        
        return [

            'driver_name_ar'        => ['bail', 'required', 'string', 'max:255'],
            'driver_name_en'        => ['bail', 'required', 'string', 'max:255'],


            'notes_ar' => ['bail', 'required', 'string', 'max:1024'],
            'notes_en' => ['bail', 'required', 'string', 'max:1024'],

            'car_number'          => ['bail','string', 'required', ],
            'car_model'         => ['bail','required', 'string' ,],
            'car_year_made'         => ['bail','required','string'],
            'driver_license_number'         => ['bail', 'string'],
            'driver_phone'         => ['bail', 'numeric'],
            'is_available'         => ['bail', 'boolean'],
            'car_type'         => ['bail','numeric'],
        ];
    }
    public function saveData(): array
    {
        $data["ar"]["driver_name"] = $this->driver_name_ar;
        $data["en"]["driver_name"] = $this->driver_name_en;
        $data["ar"]["notes"] = $this->notes_ar;
        $data["en"]["notes"] = $this->notes_en;

        $data["car_number"] = $this->car_number;
        $data["car_model"] = $this->car_model;
        $data["car_year_made"] = $this->car_year_made;
        $data["driver_license_number"] = $this->driver_license_number;
        $data["driver_phone"] = $this->driver_phone;
        $data["is_available"] = $this->is_available;
        $data["car_type"] = $this->car_type;


        return $data;
    }
}
