<?php

namespace App\Http\Resources; 

use Illuminate\Http\Resources\Json\JsonResource;  

class PermissionResource extends JsonResource
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
            'name' => $this->name,
            'created_at_formatted' => $this->when($this->created_at, function () {
                return $this->created_at->toDayDateTimeString();
            }),
            'can' => [
                'edit' => $request->user()?->can('edit permission'),
                'delete' => $request->user()?->can('delete permission'),
            ],
        ];
    } 
}
