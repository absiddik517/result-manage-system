<?php

namespace App\Http\Requests\Academic\Result\Index;

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
                        'student_id' => 'required|min:4',
            'class_id' => 'required|min:4',
            'exam_id' => 'required|min:4',
            'subject_id' => 'required|min:4',
            'total_mark_obtain' => 'required|min:4',
            'point' => 'required|min:4',
            'grade' => 'required|min:4',
            'status' => 'required|min:4',
            'result' => 'required|min:4',
        ];
    }
}
