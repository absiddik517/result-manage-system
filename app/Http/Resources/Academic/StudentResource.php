<?php

namespace App\Http\Resources\Academic;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
      'class_name' => $this->classs->name,
      'roll' => $this->roll,
      'name' => $this->name,
      'gender' => $this->gender,
      'section' => $this->section,
      'group' => optional($this->group)->name ?? null,
      'optional' => optional($this->subject)->name ?? null,
      'delete_url' => route('student.delete', $this->id),
      'edit_url' => route('student.update', $this->id),
    ];
  }
}