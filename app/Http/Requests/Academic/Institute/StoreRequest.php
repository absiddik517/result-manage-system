<?php

namespace App\Http\Requests\Academic\Institute;

use Illuminate\Foundation\Http\FormRequest;

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
    return [
      "language" => "required",
      "name" => "required|min:4",
      "established_at" => "required",
      "address" => "required|min:4",
      "logo" => "nullable",
    ];
  }
}
