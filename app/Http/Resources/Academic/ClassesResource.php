<?php

namespace App\Http\Resources\Academic;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassesResource extends JsonResource
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
      'name' => $this->name,
      'short_name' => $this->short_name,
      'has_group' => $this->has_group,
      'total_subjects' => count($this->subjects),
      'subjects' => SubjectResourceDeep::collection($this->subjects),
      'delete_url' => route('classes.delete', $this->id),
      'edit_url' => route('classes.update', $this->id),
    ];
  }
}