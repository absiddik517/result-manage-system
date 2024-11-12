<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueIf implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $table;
    public $field;
    public $except;
    public $exist;
    
    public function __construct($request_if, $table, $field, $except = null)
    {
      $this->table = $table;
      $this->field = $field;
      $this->except = $except;
      
      $field_value = request()->input($request_if);
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
      if(!$this->exist) return true;
      $result = \DB::table($this->table)->where($this->field, $value)
                ->when($this->except, function($join, $id) {
                  $join->where('id', '!=', $id);
                })->count();
      return $result == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is already exist.';
    }
}
