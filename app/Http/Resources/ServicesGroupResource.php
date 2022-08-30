<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicesGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,  

             // name on curren locale -> use in index
            'name' => $this->when($this->name, $this->name),
            // name in specific lang -> use in create and update          -- don't forget to modify items in index.vue and create.vue
            'name_ar' => $this->whenNotNull($this->translate('ar')->name), // this with return null if database doesn't hav data in arabic
            // 'name_ar' => $this->whenNotNull($this->translate('en')->name), // delete this if database has arabic content and activate above one this will keep showing name_ar and name_en both in english if not changed to ar but if database has no arabic data it will return error in index becase name ar is null
            'name_en' => $this->whenNotNull($this->translate('en')->name),
            
            'notes' => $this->when($this->name, $this->notes),
            'notes_ar' => $this->whenNotNull($this->translate('ar')->notes), 
            'notes_en' => $this->whenNotNull($this->translate('en')->notes),
            

            'status' => $this->whenNotNull($this->status),
            

            'discount_value' => $this->whenNotNull($this->discount_value),
            'Total_before_discount' => $this->whenNotNull($this->Total_before_discount),
            'Total_after_discount' => $this->whenNotNull($this->Total_after_discount),
            

            'tax_rate' => $this->whenNotNull($this->tax_rate),
            'Total_with_tax' => $this->whenNotNull($this->Total_with_tax),
            

            'created_at_formatted' => $this->when($this->created_at, function () {
                return $this->created_at->toDayDateTimeString();
            }),

            // 'services' => ServiceResource::collection($this->whenLoaded('services')),
            // 'servicesGroup' => $this->whenNotNull($this->whenLoaded('services')),
            // 'servicesInGroup' => ServiceResource::collection($this->whenLoaded('services')),




            'can' => [
                'edit' => $request->user()?->can('edit service'),
                'delete' => $request->user()?->can('delete service'), 
            ], 
        ];
    }
}
