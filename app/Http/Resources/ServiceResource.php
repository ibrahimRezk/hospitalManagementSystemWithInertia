<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;  

class ServiceResource extends JsonResource 
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable 
     */
    public function toArray($request)
    {
                // important ... i modified the seeder for categories , users , and products to be dynamic for multiple languages ... take a look at it

        return [
            'id' => $this->id,  

             // name on curren locale -> use in index
            'name' => $this->when($this->name, $this->name),
            // name in specific lang -> use in create and update          -- don't forget to modify items in index.vue and create.vue
            'name_ar' => $this->whenNotNull($this->translate('ar')->name), // this with return null if database doesn't hav data in arabic
            // 'name_ar' => $this->whenNotNull($this->translate('en')->name), // delete this if database has arabic content and activate above one this will keep showing name_ar and name_en both in english if not changed to ar but if database has no arabic data it will return error in index becase name ar is null
            'name_en' => $this->whenNotNull($this->translate('en')->name),
            
            'description' => $this->when($this->name, $this->description),
            'description_ar' => $this->whenNotNull($this->translate('ar')->description), 
            'description_en' => $this->whenNotNull($this->translate('en')->description), 


            'price' => $this->whenNotNull($this->price), 
            'status' => $this->whenNotNull($this->status),
            
            'created_at_formatted' => $this->when($this->created_at, function () {
                return $this->created_at->toDayDateTimeString();
            }),




            'can' => [
                'edit' => $request->user()?->can('edit service'),
                'delete' => $request->user()?->can('delete service'), 
            ], 
        ];
    }
}
