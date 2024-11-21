<?php

namespace App\Http\Controllers\Academic;

use Exception;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Helper\Traits\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\Student\StoreRequest;
use App\Http\Requests\Academic\Student\UpdateRequest;
use App\Http\Resources\Academic\StudentResource;

class StudentController extends Controller
{
    use Filter;
    public function index(Request $req){
      $classes = Classes::select(['id as value', 'name as label'])->get();
      $students = StudentResource::collection(
        Student::leftJoin('groups', 'groups.id', '=', 'students.group_id')
        ->join('classes', 'classes.id', '=', 'students.class_id')
        ->when($req->search, function($query) use($req){
          $query->orWhere('students.name', 'like', '%'.$req->search.'%')
              ->orWhere('groups.name', 'like', '%'.$req->search.'%')
              ->orWhere('classes.name', 'like', '%'.$req->search.'%')
              ->orWhere('students.roll', $req->search);
        })
        ->select('students.id', 'students.class_id', 'students.roll', 'students.name', 'groups.name as group', 'classes.name as classs')
        ->paginate($req->per_page ?? 5)->withQueryString()
      );
      //return $students;
      $params = $req->input();
      return inertia('Academic/Student/Index', compact('students', 'params', 'classes'));
    }
    
    public function create(Request $req){
      $classes = pluckByKey(Classes::select('id', 'name', 'has_group')->get());
      $groups = \DB::table('groups')->select('name', 'id')->get();
      $class_id = $req->class_id;
      return inertia('Academic/Student/Create', compact('classes', 'groups', 'class_id'));
    }
    
    public function store(StoreRequest $req){
      try{
        Student::create($req->validated());
        $toast = [
          'message' => 'Student has <kbd>created</kbd> successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => $e->getMessage(), 
          'type' => 'error'
        ];
      }
      return redirect()->route('student.create', ['class_id' => $req->class_id])->with(['toast' => $toast]);
      return redirect()->route('student.index')->with('toast', $toast);
    }
    
    public function update($id, UpdateRequest $req){
      try{
        $student = Student::find($id);
        $student->update($req->validated());
        $toast = [
          'message' => 'Student <strong>'.$student->name.'</strong> has <kbd>updated</kbd> successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => exception_message($e), 
          'type' => 'error'
        ];
      }
      return redirect()->back()->with('toast', $toast);
    }
    
    public function destroy($id){
      //sleep(5);
      try{
        $student = Student::findOrFail($id);
        $student->delete();
        $toast = [
          'message' => 'Student <strong>'.$student->name.'</strong> has <kbd>deleted</kbd> successfull!', 
          'type' => 'success'
        ];
      }catch(\Exception $e){
        $toast = [
          'message' => exception_message($e), 
          'type' => 'error'
        ];
      }
      return redirect()->back()->with('toast', $toast);
    }
    
    public function get_roll(Request $req){
      $data = Student::where(function($q) use($req){
                if($req->has('class_id')){
                  $q->where('class_id', $req->input('class_id'));
                }
              })
              ->orderBy('id', 'desc')->first();
      if(!$data) return 1;
      return $data->roll+1;
    }
    
    public function get_students(Request $req){
      $student = Student::where(function($query) use($req){
        if($req->has('class_id')){
          $query->where('class_id', $req->class_id);
        }
      })->where(function($query) use($req){
        if($req->has('roll')){
          $query->where('roll', $req->roll);
        }
      })->where(function($query) use($req){
        if($req->has('name')){
          $query->where('name', 'like', '%'.$req->name.'%');
        }
      })
      ->select('id as value', 'name as label')
      ->get();
      return $student;
    }
}
