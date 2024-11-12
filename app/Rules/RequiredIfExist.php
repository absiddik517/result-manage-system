<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredIfExist implements Rule
{
    private $field;
    private $type;
    private $exist = false;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($field = null)
    {
      $this->field = $field;
      $field_value = request()->input($field);
      $type = gettype($field_value);
      if($type == 'integer' && $field_value > 0){
        $this->exist = true;
      }
      
      else if($type == 'string' && $field_value != ''){
        $this->exist = true;
      }
      
      else if($type == 'boolean' && $field_value === true){
        $this->exist = true;
      }
      else {
        $this->exist = false;
      }
      
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
      if($this->exist) {
        return $value != '';
      }
      return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute is required when $this->field exist.";
    }
}
