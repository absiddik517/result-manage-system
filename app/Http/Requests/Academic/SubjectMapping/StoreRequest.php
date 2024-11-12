<?php

namespace App\Http\Requests\Academic\SubjectMapping;

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
            'exam_id' => 'required',
            'class_id' => 'required',
            'mappings.*.full_mark' => 'required|gte:10',
            'mappings.*.id' => 'nullable',
            'mappings.*.exam_id' => 'nullable',
            'mappings.*.class_id' => 'nullable',
            'mappings.*.subject_id' => 'nullable',
            'mappings.*.criteria.*.title' => 'required|min:3',
            'mappings.*.criteria.*.short_title' => 'required|min:2',
            'mappings.*.criteria.*.full_mark' => 'required|gte:5',
            'mappings.*.criteria.*.pass_mark' => 'required|gte:0',
        ];
    }
    
    public function attributes()
    {
        return [
            'exam_id' => 'exam',
            'class_id' => 'class',
            'mappings.*.full_mark' => 'full mark',
            'mappings.*.criteria.*.title' => 'title',
            'mappings.*.criteria.*.short_title' => 'short title',
            'mappings.*.criteria.*.full_mark' => 'full mark',
            'mappings.*.criteria.*.pass_mark' => 'pass mark',
        ];
    }
}
