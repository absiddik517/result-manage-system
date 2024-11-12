<?php

namespace App\Models;

use App\Helper\Traits\HasDefault;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    public $guarded = [];
    protected $perPage = 5;
    //protected static $hasDefault = ['season_id', 'user_id', 'date'];
    
    public function classs(){
      return $this->belongsTo(Classes::class, 'class_id', 'id');
    }
    public function group(){
      return $this->belongsTo(Group::class, 'group_id', 'id');
    }
    
    public function subject(){
      return $this->belongsTo(Subject::class, 'optional_subject_id', 'id');
    }
}
