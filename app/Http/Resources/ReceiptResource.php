<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
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

            'patient' => new SectionResource($this->whenLoaded('patient')),

            'amount' => $this->when($this->amount, $this->amount),
            'description' => $this->when($this->description, $this->description),
                
            'created_at_formatted' => $this->when($this->created_at, function () {
                return $this->created_at->toDayDateTimeString();
            }),

            'can' => [
                'edit' => $request->user()?->can('edit receipt'),
                'delete' => $request->user()?->can('delete receipt'), 
            ], 
        ];
    }
}
