<?php

namespace App\Http\Requests\Academic\SubjectMapping;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
                        'exam_id' => 'required|min:4',
            'class_id' => 'required|min:4',
            'subject_id' => 'required|min:4',
            'full_mark' => 'required|min:4',
            'criteria' => 'required|min:4',
        ];
    }
}
