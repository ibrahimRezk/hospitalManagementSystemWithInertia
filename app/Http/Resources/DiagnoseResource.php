<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiagnoseResource extends JsonResource
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
            'date' => $this->whenNotNull($this->date), 
            'review_date' => $this->whenNotNull($this->review_date),
            'diagnose' => $this->whenNotNull($this->diagnose),
            'medicine' => $this->whenNotNull($this->medicine),
            'invoice_id' => $this->whenNotNull($this->invoice_id),
            'patient_id' => $this->whenNotNull($this->patient_id),
            'doctor_id' => $this->whenNotNull($this->doctor_id),
            'date' => $this->whenNotNull($this->date),
            
            'doctor'    => new DoctorResource($this->whenLoaded('doctor')),

        ];
    }
}
