<?php

namespace App\Http\Resources\Academic;

use Illuminate\Http\Resources\Json\JsonResource;

class InstituteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      //return parent::toArray($request);
      return [
        'id' => $this->id,
                'language'       => $this->language,
        'name'       => $this->name,
        'established_at'       => $this->established_at,
        'address'       => $this->address,
        'logo'       => $this->logo,
        'delete_url' => route('institute.delete', $this->id),
        'edit_url'   => route('institute.update', $this->id),
      ];
    }
}
