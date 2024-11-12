<?php

if(!function_exists('exception_message')){
  function exception_message($e){
    return $e->getMessage();
  }
}

if(!function_exists('pluckByKey')){
  function pluckByKey($array, $pluck_by = 'id', $remove = false){
    if(gettype($array) == 'object'){
      if($array->isEmpty()) return [];
      $array = $array->toArray();
    }
    $return_array = [];
    foreach ($array as $item){
      $array_index = $item[$pluck_by];
      if($remove) unset($item[$pluck_by]);
      $return_array[$array_index] = $item;
    }
    return $return_array;
  }
}

if(!function_exists('parse_input_options')){
  function parse_input_options($items, $id = 'id', $name = 'name') {
    if(gettype($items) == 'object'){
      if($items->isEmpty()) return [];
      $items = $items->toArray();
    }
    \Log::channel('single')->warning('parse_input_options', [
      'result' => $items
    ]);
    $result = [];
    foreach ($items as $item){
      $result[] = [
        'id'   => $item[$id],
        'name' => $item[$name],
      ];
    }
    return $result;
  }
}

if(!function_exists('array_only_value')){
  function array_only_value($items, $key = 'name'){
    if(gettype($items) == 'object'){
      if($items->isEmpty()) return [];
      $items = $items->toArray();
    }
    $return_array = [];
    foreach ($items as $item){
      $return_array[] = $item[$key];
    }
    return $return_array;
  }
}

if(!function_exists('gate_allows')){
  function gate_allows(string $permission) {
    $permissions = session()->get('permissions');
    return in_array($permission, $permissions);
  }
}

if(!function_exists('gate_denies')){
  function gate_denies($permission) {
    return !gate_allows($permission);
  }
}

if(!function_exists('gate_allow_any')){
  function gate_allow_any(array $permissions){
    foreach ($permissions as $permission){
      if(gate_allows($permission)) return true;
    }
    return false;
  }
}

if(!function_exists('gate_allow_all')){
  function gate_allow_all(array $permissions){
    foreach ($permissions as $permission){
      if(gate_denies($permission)) return false;
    }
    return true;
  }
}

if (!function_exists('bnum')) {
    function bnum($number) {
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
        $bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '.'];

        return str_replace($englishDigits, $bengaliDigits, strval($number));
    }
}


