<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueRoll implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $table = "students";
    private $class_id;
    public function __construct()
    {
        $this->class_id = request()->input('class_id'); 
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $data = \DB::table('students')->where('class_id', $this->class_id)
                  ->where('roll', $value)
                  ->count();
        return $data === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Roll number already exist.';
    }
}
