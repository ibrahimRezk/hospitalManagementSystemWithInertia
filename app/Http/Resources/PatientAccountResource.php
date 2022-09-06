<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientAccountResource extends JsonResource
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
            

            'Debit' => $this->when($this->Debit, $this->Debit),
            'credit' => $this->when($this->credit, $this->credit),
            
            'payment'=> new PaymentResource($this->whenLoaded('payment')),
            'invoice'=> new InvoiceResource($this->whenLoaded('invoice')),
            'receipt'=> new ReceiptResource($this->whenLoaded('receipt')),

            

            'created_at_formatted' => $this->when($this->created_at, function () {
                return $this->created_at->toDayDateTimeString();
            }),
        
        ];
    }
}
