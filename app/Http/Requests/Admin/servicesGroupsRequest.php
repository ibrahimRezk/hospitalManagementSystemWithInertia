<?php

namespace App\Http\Requests\Admin;

use App\Models\GroupTranslation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class servicesGroupsRequest extends FormRequest
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
        // this is very important to ignore some thing in translations 
        //in create we don't have id but in edit we have  so we start in condition that we asume we have id witch is $this->section->id   if not we jump to other choice witch is $this->id witch we don't have yet so it will jump to the last section witch is null :)
        // $group_ar = (($this->group->id ?? $this->id) ?  $this->group->translate('ar')->id  : null);
        // $group_en = (($this->group->id ?? $this->id) ?  $this->group->translate('en')->id  : null);

        $group_ar = $this->group?->translate('ar')->id   ;
        $group_en =  $this->group?->translate('en')->id  ;
        return [

            'name_ar'        => ['bail', 'required', 'string', 'max:255', Rule::unique(GroupTranslation::class, 'name')->ignore($group_ar)],
            'name_en'        => ['bail', 'required', 'string', 'max:255', Rule::unique(GroupTranslation::class, 'name')->ignore($group_en)],
            'notes_ar'       => ['bail', 'required', 'string', 'max:1024'],
            'notes_en'       => ['bail', 'required', 'string', 'max:1024'],

        ];
    }
    public function saveData(): array
    {
        $data["ar"]["name"] = $this->name_ar;
        $data["en"]["name"] = $this->name_en;
        $data["ar"]["notes"] = $this->notes_ar;
        $data["en"]["notes"] = $this->notes_en;
        $data["Total_before_discount"] = $this->Total_before_discount;
        $data["discount_value"] = $this->discount_value;
        $data["Total_after_discount"] = $this->Total_after_discount;
        $data["tax_rate"] = $this->tax_rate;
        $data["Total_with_tax"] = $this->Total_with_tax;
        $data["status"] = $this->status;
        return $data;
    }
}
