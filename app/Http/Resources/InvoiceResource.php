<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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

            // very important for viewing name in a relation in index    here  and in controller index we put field name like doctor_id and with relation like with(['doctor:id'])
            'doctor'    => new DoctorResource($this->whenLoaded('doctor')),
            'patient'   => new PatientResource($this->whenLoaded('patient')),
            'service'   => new ServiceResource($this->whenLoaded('service')),
            'section'   => new SectionResource($this->whenLoaded('section')),
            'group'     => new ServicesGroupResource($this->whenLoaded('group')),

    
            'invoice_type' => $this->whenNotNull($this->invoice_type), 
            'discount_value' => $this->whenNotNull($this->discount_value), 
            'tax_rate' => $this->whenNotNull($this->tax_rate), 
            'tax_value' => $this->whenNotNull($this->tax_value), 
            'total_with_tax' => $this->whenNotNull($this->total_with_tax), 
            'type' => $this->whenNotNull($this->type), 
            'price' => $this->whenNotNull($this->price), 
            'invoice_status' => $this->whenNotNull($this->invoice_status),
            
            'created_at_formatted' => $this->when($this->created_at, function () {
                return $this->created_at->toDayDateTimeString();
            }),
            'can' => [
                'edit' => $request->user()?->can('edit invoice'),
                'delete' => $request->user()?->can('delete invoice'), 
            ], 
        ];
    }
}
