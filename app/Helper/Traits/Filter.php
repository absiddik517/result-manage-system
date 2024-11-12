<?php
namespace App\Helper\Traits;

trait Filter{
  private $fields;
  
  function getFilterQuery($model, $fields, $with = null){
    $likeFields = $fields['like'] ?? [];
    $equalFields = $fields['equal'] ?? [];
    $this->fields = [...$likeFields, ...$equalFields];
    
    request()->validate([
      'orderDirection' => 'in:asc,desc,ASC,DESC',
      'orderBy' => 'in:id,'. join(',', $this->fields),
      'per_page' => 'integer|min:1'
    ]);
    
    $query = (new $model)->query();
    
    if(request()->has('orderBy') && request()->has('orderDirection')){
      $query->orderBy(request()->orderBy, request()->orderDirection);
    }else{
      $query->orderBy('id', 'DESC');
    }
    if(request()->has('id')) $query->where('id', request()->id);
    foreach ($likeFields as $field){
      if(request()->has($field)) $query->where($field, 'like', "%". request()->$field ."%");
    }
    foreach ($equalFields as $field){
      if(request()->has($field)) $query->where($field, request()->$field);
    } 
    
    if(request()->has('search')){
      foreach ($likeFields as $field){
        $query->orWhere($field, 'like', "%". request()->search ."%");
      }
      foreach ($equalFields as $field){
        $query->orWhere($field, request()->search);
      }
    }
    
    if($with){
      $query->with($with);
    }
    
    return $query;
  }
  
  public function getFilterData($model, $fields, $with = null){
    $query = $this->getFilterQuery($model, $fields, $with);
    if(request()->has('per_page')){
      return $query->paginate(request()->per_page);
    }
    return $query->paginate();
  }
  
  public function getParams(){
    if($this->fields) return request()->only(['orderBy', 'per_page', 'orderDirection', 'id', 'search', ...$this->fields]);
    return request()->only(['orderBy', 'per_page', 'orderDirection', 'id', 'search']);
  }
  
  
}