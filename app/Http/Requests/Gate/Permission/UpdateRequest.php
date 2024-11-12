<?php

namespace App\Http\Requests\Gate\Permission;

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
        return gate_allows('update_permission');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
                        'name' => 'required|min:4',
            'guard_name' => 'required|min:4',
            'group_name' => 'required|min:4',
        ];
    }
}
