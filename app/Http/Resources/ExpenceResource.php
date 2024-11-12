<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenceResource extends JsonResource
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
        'record' => $this->record,
        'id' => $this->id,
        'description' => $this->description,
        'amount' => $this->amount,
        'through' => $this->through,
        'date' => date('d M Y', strtotime($this->date)),
        'author_name' => $this->author_name,
      ];
    }
}
