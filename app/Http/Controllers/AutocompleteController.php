<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AutocompleteController extends Controller
{
  public function autocomplete(){
    $tables = [
      'users',
      'customer_payments',
      'order_deliveries',
      'order_items',
      'customers',
      'products',
      'orders',
      'costs',
      'submit_cashes',
      'deposits',
      'staff_payments',
      'worker_payments',
    ];
    request()->validate([
      't' => 'required|in:'.join(',', $tables), 
      'f' => 'required',
      'v' => 'required'
    ]);
    
    $data = DB::table(request()->input('t'))
        ->when(request()->input('additional'), function($join, $fields) {
          $join->select([request()->input('f'), ...request()->input('additional')]);
        })
        ->when(request()->input('f'), function($join, $fields) {
          if(!request()->input('additional') || count(request()->input('additional')) == 0){
            $join->select(request()->input('f'));
          }
        })
        ->where(request()->input('f'), 'like', '%'.request()->input('v').'%')
        ->distinct()
        ->limit(5)->get();
        
        //return $data;
    
    
    return $this->plainArray($data, request()->input('f'), request()->input('additional'));
  }
  
  public function customers(){
    $result = DB::table('customers')
      ->where('name', 'like', '%'. request()->input('v') .'%')
      ->orWhere('phone', 'like', '%'. request()->input('v') .'%')
      ->orWhere('id', request()->input('v'))
      ->select('id', 'name', 'address', 'phone')
      ->distinct()
      ->limit(6)->get();
    return $result;
  }
  
  private function plainArray($arr, $key, $additional = false){
    $return_array = [];
    foreach ($arr as $item){
      $row = [
        $key => $item->$key,
      ];
      if($additional && gettype($additional) == 'array' && count($additional) > 0){
        foreach ($additional as $field){
          $row[$field] = $item->$field;
        }
      }
      $return_array[] = $row;
    }
    return $return_array;
  }
}
