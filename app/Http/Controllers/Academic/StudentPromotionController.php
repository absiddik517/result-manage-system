<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Classes;
use App\Models\Student;

class StudentPromotionController extends Controller
{
    public function index(Request $req){
      $classes = Classes::select(['id', 'name', 'has_group'])->get();
      return inertia('Academic/Student/Promotion', compact('classes'));
    }
    
    public function getStudents($class_id){
      $students = Student::select('id', 'name', 'roll', 'group_id', 'optional_subject_id')
                  ->where('class_id', $class_id)
                  ->get();
      if($students->count() === 0) abort(404, 'Student not found.');
      return $students;
    }
}
