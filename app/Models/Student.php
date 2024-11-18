<?php

namespace App\Models;

use App\Helper\Traits\HasDefault;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    public $guarded = [];
    public $hidden = ['created_at', 'updated_at'];
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
    
    public function subjec(){
      return \DB::table('class_subjects')
              ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.id')
              ->where('class_id', $this->class_id)
              ->whereNull('subjects.group_id')
              ->orWhere('subjects.group_id', $this->group_id)
              ->select('subjects.name', 'subjects.id')
              ->get();
    }
    
    public function subjects()
    {
        $groupId = $this->group_id;
        //dd($this->group_id);
        return $this->hasManyThrough(
            Subject::class,
            ClassSubject::class,
            'class_id', // Foreign key on class_subjects table
            'id',       // Foreign key on subjects table
            'class_id', // Local key on students table
            'subject_id' // Local key on class_subjects table
        )
        ->where(function($query){
          $query->whereNull('subjects.group_id')
          ->orWhere('subjects.group_id', $this->group_id);
        })
        ->orderBy('subjects.id', 'asc');
        
    }
}

