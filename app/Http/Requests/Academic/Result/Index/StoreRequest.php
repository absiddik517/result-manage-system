<?php

namespace App\Http\Requests\Academic\Result\Index;

use Illuminate\Foundation\Http\FormRequest;

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
        'results.*.id' => 'nullable',
        'results.*.appeared' => 'required|boolean',
        'results.*.student_id' => 'required_if:results.*.appeared,true',
        'results.*.class_id' => 'required_if:results.*.appeared,true',
        'results.*.exam_id' => 'required_if:results.*.appeared,true',
        'results.*.group_id' => 'nullable',
        'results.*.subject_id' => 'required_if:results.*.appeared,true',
        'results.*.total_mark_obtain' => 'required_if:results.*.appeared,true',
        'results.*.point' => 'required_if:results.*.appeared,true',
        'results.*.grade' => 'required_if:results.*.appeared,true',
        'results.*.status' => 'required_if:results.*.appeared,true',
        'results.*.result.*.title' => 'required_if:results.*.appeared,true',
        'results.*.result.*.short_title' => 'required_if:results.*.appeared,true',
        'results.*.result.*.full_mark' => 'required_if:results.*.appeared,true',
        'results.*.result.*.mark_obtain' => 'required_if:results.*.appeared,true',
        'results.*.result.*.status' => 'required_if:results.*.appeared,true',
    ];
  }
  
  public function messages(){
    return [
      'results.*.result.*.mark_obtain.required_if' => 'Fill out this field.',
    ];
  }
}