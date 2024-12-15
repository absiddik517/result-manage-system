<?php

namespace App\Http\Requests\Academic\Promotion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */

  public function rules()
  {
    $students = $this->input("students", []); // Retrieve all student data
    $has_group = $this->input("has_group", 0);
    $rules = [];

    foreach ($students as $index => $student) {
      $rules["students.$index.roll"] = Rule::requiredIf(
        isset($student["promoted"]) && $student["promoted"] === true
      );
      $rules["students.$index.group_id"] = Rule::requiredIf(
        isset($student["promoted"]) && $student["promoted"] === true && $has_group === 1
      );
      $rules["students.$index.optional_subject_id"] = Rule::requiredIf(
        isset($student["promoted"]) && $student["promoted"] === true && $has_group === 1
      );
    }

    return $rules;
  }
  
  public function attributes(){
    $students = $this->input("students", []); 
    $rules = [];

    foreach ($students as $index => $student) {
      $rules["students.$index.roll"] = "roll";
      $rules["students.$index.group_id"] = "group";
      $rules["students.$index.optional_subject_id"] = "subject";
    }
    return $rules;
  }
}
