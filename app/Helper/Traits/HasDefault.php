<?php
    
    namespace App\Helper\Traits;
    
    trait HasDefault {
      
      protected static function bootHasDefault()
      {
        static::creating(function ($model) {
          $columnValues = self::getValuesOfDefaults();
          foreach (self::getDefalutValueColumns() as $column){
            if(isset($columnValues[$column])){
              $model->$column = $columnValues[$column];
            }
          }
        });
        /*
        static::created(function($model) {
          $keeps = self::getKeepsColumn($model);
          if(count($keeps) == 0) return;
          \App\Model\People::create(self::getKeepsValue($model, $keeps));
        });
        */
      }
      
      private static function getDefalutValueColumns():array
      {
        if (property_exists(self::theClass(), 'hasDefault')) return self::$hasDefault;
        return [];
      }
      
      private static function getKeepsColumn($model):array
      {
        if (property_exists(self::theClass(), 'keeps')) return self::$keeps;
        
        $match = ['name', 'address', 'phone', 'provider', 'email'];
        $output = [];
        foreach (array_keys($model->toArray()) as $key){
          if(in_array($key, $match)) $output[] = $key;
        }
        return $output;
      }
      
      private static function getKeepsValue($model, array $keeps){
        $subject = app(self::theClass())->getTable();
        $values = ['subject' => $subject];
        foreach ($keeps as $column){
          $value = $model->$column;
          if($column == 'provider') $column = 'name';
          $values[$column] = $value;
        }
        return $values;
      }
      
      private static function getValuesOfDefaults(){
        return [
          'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
          'date' => date('Y-m-d'),
          'season_id' => 1
          //'date' => \Dates::Today(),
          //'season_id' => session()->get('season_id'),
        ];
      }
      
      private static function theClass(){
          return static::class;
      }
      
      
      
    }