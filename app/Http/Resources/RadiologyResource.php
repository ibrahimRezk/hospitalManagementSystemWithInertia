<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RadiologyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable 
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,

            // very important for viewing name in a relation in index    here  and in controller index we put field name like doctor_id and with relation like with(['doctor:id'])
            'doctor'    => new DoctorResource($this->whenLoaded('doctor')),
            'employee'   => new LaboratoristResource($this->whenLoaded('employee')),
            'patient'   => new PatientResource($this->whenLoaded('patient')),
            'invoice'   => new InvoiceResource($this->whenLoaded('invoice')),

            'description' => $this->whenNotNull($this->description),
            'employee_description' => $this->whenNotNull($this->employee_description),
            'status' => $this->whenNotNull($this->status),
            

            'created_at_formatted' => $this->when($this->created_at, function () { 
                return $this->created_at->toDayDateTimeString();
            }),
            'images' => $this->whenLoaded(
                'media',
                fn () => $this->getMedia()->map(      /////////////////// important  to get images collection
                    fn ($media) => [
                        'id' => $media->id,
                        'html' => $media->toHtml(),
                        'img' => $media->original_url,
                    ]
                )
            ),
            // 'can' => [
            //     'edit' => $request->user()?->can('edit invoice'),
            //     'delete' => $request->user()?->can('delete invoice'),
            // ],
        ];
    }
}
