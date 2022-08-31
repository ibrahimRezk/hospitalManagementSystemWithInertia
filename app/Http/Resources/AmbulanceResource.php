<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AmbulanceResource extends JsonResource
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
            'driver_name' => $this->when($this->driver_name, $this->driver_name),
             // name in specific lang -> use in create and update          -- don't forget to modify items in index.vue and create.vue
            'driver_name_ar' => $this->whenNotNull($this->translate('ar')->driver_name), // this with return null if database doesn't hav data in arabic
            // 'name_ar' => $this->whenNotNull($this->translate('en')->name), // delete this if database has arabic content and activate above one this will keep showing name_ar and name_en both in english if not changed to ar but if database has no arabic data it will return error in index becase name ar is null
            'driver_name_en' => $this->whenNotNull($this->translate('en')->driver_name),

            'notes' => $this->when($this->name, $this->notes),
            'notes_ar' => $this->whenNotNull($this->translate('ar')->notes), 
            'notes_en' => $this->whenNotNull($this->translate('en')->notes),

            'car_number',
                'car_model',
                'car_year_made',
                'driver_license_number',
                'driver_phone',
                'is_available',
                'car_type',
                'created_at',

            'car_number' => $this->whenNotNull($this->car_number),

            'car_model' => $this->whenNotNull($this->car_model),

            'car_year_made' => $this->whenNotNull($this->car_year_made),
            
            'driver_license_number' => $this->whenNotNull($this->driver_license_number),
            'driver_phone' => $this->whenNotNull($this->driver_phone),
            'is_available' => $this->whenNotNull($this->is_available),
            'car_type' => $this->whenNotNull($this->car_type),
            
            'created_at_formatted' => $this->when($this->created_at, function () {
                return $this->created_at->toDayDateTimeString();
            }),

            'can' => [
                'edit' => $request->user()?->can('edit ambulance'),
                'delete' => $request->user()?->can('delete ambulance'), 
            ], 
        ];
    }
}
