<?php

namespace App\Http\Resources\Academic;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
  /**
  * Transform the resource into an array.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
  */
  public function toArray($request) {
    //return parent::toArray($request);
    return [
      'id' => $this->id,
      'class_id' => $this->class_id,
      'name' => $this->name,
      'short_name' => $this->short_name,
      'delete_url' => route('subject.delete', $this->id),
      'edit_url' => route('subject.update', $this->id),
    ];
  }
}