<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceTranslation;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;


class SingleServiceRequest extends FormRequest
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
        $service_ar =( ($this->service->id ?? $this->id ) ?  $this->service->translate('ar')->id  : null) ;
        $section_en =( ($this->service->id ?? $this->id ) ?  $this->service->translate('en')->id  : null) ;
       return [
           
           'name_ar'        => ['bail', 'required', 'string', 'max:255' ,Rule::unique(ServiceTranslation::class,'name' )->ignore( $service_ar )],
           'name_en'        => ['bail', 'required', 'string', 'max:255' ,Rule::unique(ServiceTranslation::class,'name' )->ignore( $section_en )],
           'description_ar' => ['bail', 'required', 'string', 'max:1024'],
           'description_en' =>['bail', 'required', 'string', 'max:1024'],
           'price'          => ['bail', 'required', 'numeric', 'max:500' ],
           'status'         => ['bail', 'boolean'],
       ];


   }
   public function saveData(): array
    {
       $data["ar"]["name"] = $this->name_ar;
       $data["en"]["name"] = $this->name_en;
       $data["ar"]["description"] = $this->description_ar;
       $data["en"]["description"] = $this->description_en;
       $data["price"] = $this->price;
       $data["status"] = $this->status;


       return $data;

    }
    
}
