<?php

namespace App\Http\Requests\Admin;

use App\Models\Section;
use App\Models\SectionTranslation;
use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class SectionsRequest extends FormRequest
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
     * @return array
     */
    public function rules() 
    {
        
        
        // this is very important to ignore some thing in translations 
        //in create we don't have id but in edit we have  so we start in condition that we asume we have id witch is $this->section->id   if not we jump to other choice witch is $this->id witch we don't have yet so it will jump to the last section witch is null :)
       
         $section_ar = $this->section?->translate('ar')->id   ;
        $section_en =  $this->section?->translate('en')->id  ;
        return [
            
            'name_ar' => ['bail', 'required', 'string', 'max:255' ,Rule::unique(SectionTranslation::class,'name' )->ignore( $section_ar )],
            'name_en' => ['bail', 'required', 'string', 'max:255' , Rule::unique(SectionTranslation::class,'name')->ignore( $section_en)],
            'description_ar' => ['bail', 'nullable', 'string', 'max:500' ],
            'description_en' => ['bail', 'nullable', 'string', 'max:500' ],
        ];


    }
    public function saveData(): array
     {
        $data["ar"]["name"] = $this->name_ar;
        $data["en"]["name"] = $this->name_en;
        $data["ar"]["description"] = $this->description_ar;
        $data["en"]["description"] = $this->description_en;

        return $data;

     }
}
