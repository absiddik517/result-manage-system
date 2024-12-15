<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Academic\Promotion\StoreRequest;

use App\Models\Classes;
use App\Models\Group;
use App\Models\Student;

class StudentPromotionController extends Controller
{
    public function index(Request $req){
      $groups = Group::select('id', 'name')->get();
      $classes = Classes::select(['id', 'name', 'has_group'])->get();
      return inertia('Academic/Student/Promotion', compact('classes', 'groups'));
    }
    
    public function getStudents($class_id){
      $students = Student::select('id', 'name', 'roll as old_roll', 'group_id', 'optional_subject_id')
                  ->where('class_id', $class_id)
                  ->get();
      if($students->count() === 0) abort(404, 'Student not found.');
      return $students;
    }
    
    public function promote(StoreRequest $request){
      $data = $this->cleanup($request->input('students'));
      $roll_exist = Student::where('class_id', $request->to_class_id)->whereIn('roll', $data['rolls'])->get();
      dd($roll_exist);
      try{
        foreach ($data['students'] as $id => $student){
          Student::find($id)->update($student);
        }
        $toast = [
          "type" => "success",
          "message" => "Operation successfull"
        ];
      }catch(\Exception $e){
        $toast = [
          "type" => "error",
          "message" => $e->getMessage()
        ];  
      }
      return redirect()->route('student.index')->with(["toast" => $toast]);
    }
    
    private function cleanup($data){
      $class_id = request()->input('to_class_id');
      $class = Classes::where('id', $class_id)->select('has_group')->first();
      $result = [];
      $rolls = [];
      foreach ($data as $student){
        if($student['promoted']){
          $result[$student['id']] = [
            "class_id" => $student['to_class_id'],
            "roll" => $student['roll'],
            "group_id" => $class->has_group ? $student["group_id"] : null,
            "optional_subject_id" => $class->has_group ? $student["optional_subject_id"] : null,
          ];
          $rolls[] = $student['roll'];
        }
      }
      return [
        "students" => $result,
        "rolls" => $rolls,
      ];
    }
}
