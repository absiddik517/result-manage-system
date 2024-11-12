<?php

namespace App\Models;

use App\Helper\Traits\HasDefault;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    
    public $guarded = [];
    protected $perPage = 5;
    
    public function classs()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function student(){
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function subject(){
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function exam(){
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
