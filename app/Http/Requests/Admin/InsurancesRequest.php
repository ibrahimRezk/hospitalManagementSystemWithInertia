<?php

namespace App\Http\Requests\Admin;

use App\Models\InsuranceTranslation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class InsurancesRequest extends FormRequest
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
        $insurance_ar = (($this->insurance->id ?? $this->id) ?  $this->insurance->translate('ar')->id  : null);
        $insurance_en = (($this->insurance->id ?? $this->id) ?  $this->insurance->translate('en')->id  : null);
        return [

            'name_ar'        => ['bail', 'required', 'string', 'max:255', Rule::unique(InsuranceTranslation::class, 'name')->ignore($insurance_ar)],
            'name_en'        => ['bail', 'required', 'string', 'max:255', Rule::unique(InsuranceTranslation::class, 'name')->ignore($insurance_en)],
            'notes_ar' => ['bail', 'required', 'string', 'max:1024'],
            'notes_en' => ['bail', 'required', 'string', 'max:1024'],


            'insurance_code'          => ['bail', 'required', 'max:100'],
            'discount_percentage'         => ['bail','required','numeric', 'max:100'],
            'Company_rate'         => ['bail','required', 'numeric', 'max:100'],
            'status'         => ['bail', 'boolean'],
        ];
    }
    public function saveData(): array
    {
        $data["ar"]["name"] = $this->name_ar;
        $data["en"]["name"] = $this->name_en;
        $data["ar"]["notes"] = $this->notes_ar;
        $data["en"]["notes"] = $this->notes_en;
        $data["insurance_code"] = $this->insurance_code;
        $data["discount_percentage"] = $this->discount_percentage;
        $data["Company_rate"] = $this->Company_rate;
        $data["status"] = $this->status;


        return $data;
    }
}
