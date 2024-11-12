<?php

namespace App\Models;

use App\Helper\Traits\HasDefault;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    
    public $guarded = [];
    protected $table = "classes";
    protected $perPage = 5;
    
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id', 'id');
    }
    
    public function class_subjects()
    {
        return $this->hasMany(ClassSubject::class, 'class_id', 'id');
    }
    
    
    
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id')
                    ->withPivot('group_id')
                    ->withTimestamps();
    }
}
