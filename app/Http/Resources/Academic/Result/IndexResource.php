<?php

namespace App\Http\Resources\Academic\Result;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
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
      "id" => $this->id,
      "student_id" => $this->student_id,
      "student_name" => $this->student->name,
      "class_id" => $this->class_id,
      "class_name" => $this->classs->name,
      "exam_id" => $this->exam_id,
      "exam_name" => $this->exam->name,
      "subject_id" => $this->subject_id,
      "subject_name" => $this->subject->name,
      "total_mark_obtain" => $this->total_mark_obtain,
      "point" => $this->point,
      "grade" => $this->grade,
      "status" => $this->status,
      "result" => json_decode($this->result, true),
      "delete_url" => route("result.delete", $this->id),
      "edit_url" => route("result.update", $this->id),
    ];
  }
}
