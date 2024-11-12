<?php

namespace App\Http\Requests\Academic\Student;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueRoll;

class StoreRequest extends FormRequest
{
  /**
  * Determine if the user is authorized to make this request.
  *
  * @return bool
  */
  public function authorize() {
    return true;
  }

  /**
  * Get the validation rules that apply to the request.
  *
  * @return array<string, mixed>
  */
  public function rules() {
    return [
      'class_id' => 'required',
      'roll' => ['required', new UniqueRoll() ],
      'name' => 'required|min:4',
      'gender' => 'required',
      'section' => 'nullable',
      'group_id' => 'nullable',
      'optional_subject_id' => 'nullable',
    ];
  }
}