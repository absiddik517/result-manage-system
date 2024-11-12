<?php

namespace App\Http\Resources\Academic;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectMappingResource extends JsonResource
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
      'exam_name' => $this->exam_name,
      'class_name' => $this->class_name,
      'subject_name' => $this->subject_name,
      'full_mark' => $this->full_mark,
      'criteria' => $this->criteria,
      'delete_url' => route('exam.map.delete', $this->id),
    ];
  }
}