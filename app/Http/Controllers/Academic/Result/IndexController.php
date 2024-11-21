<?php

namespace App\Http\Controllers\Academic\Result;

use Exception;
use App\Models\Exam;
use App\Models\SubjectMapping;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Helper\Traits\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\Result\Index\StoreRequest;
use App\Http\Requests\Academic\Result\Index\UpdateRequest;
use App\Http\Resources\Academic\Result\IndexResource;

class IndexController extends Controller
{
    use Filter;
    public function index(){
      $results = IndexResource::collection($this->getFilterData(Result::class, [
        'like' => ["total_mark_obtain", "point", "grade"],
        'equal' => ["student_id", "exam_id", 'class_id', "subject_id", "status"]
      ], ['student', 'exam', 'subject', 'classs'])->withQueryString());
      $params = $this->getParams();
      //dd($params);
      return inertia('Academic/Result/Index', compact('results', 'params'));
    }
    
    public function create(Request $req){
      $exams = Exam::select('id as value', 'name as label')->get();
      $classes = Classes::select('id as value', 'name as label')->get();
      $exam_id = $req->input('exam_id');
      $class_id = $req->input('class_id');
      return inertia('Academic/Result/Create', compact('exams', 'classes', 'exam_id', 'class_id'));
    }
    
    public function store(StoreRequest $req){
      $results = $this->cleanup($req->validated()['results']);
      //dd($results);
      $exam_id = null; $class_id = null;
      try{
        foreach ($results as $data){
          $exam_id = $data['exam_id']; $class_id = $data['class_id'];
          $id = $data['id'];
          $result = $data['result'];
          unset($data['id']);
          unset($data['result']);
          if($id){
            Result::find($id)->update([
            ...$data, 'result' => json_encode($result)
            ]);
          }else{
            Result::create([
              ...$data, 'result' => json_encode($result)
            ]);
          }
        }
        $toast = [
          'message' => 'Result has been <kbd>saved</kbd> successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => $e->getMessage(), 
          'type' => 'error'
        ];
      }
      return redirect()->route('result.create', ['class_id' => $class_id, 'exam_id' => $exam_id])->with([
        'toast' => $toast,
      ]);
    }
    
    private function cleanup($data){
      $output = [];
      foreach ($data as $row){
        if($row['appeared']){
          $x = $row;
          unset($x['appeared']);
          $output[] = $x;
        }
      }
      return $output;
    }
    
    public function update($id, UpdateRequest $req){
      try{
        $index = Result::find($id);
        $index->update($req->validated());
        $toast = [
          'message' => 'Index <strong>'.$index->name.'</strong> has <kbd>updated</kbd> successful!', 
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
        $index = Result::findOrFail($id);
        $index->delete();
        $toast = [
          'message' => 'Index <strong>'.$index->name.'</strong> has <kbd>deleted</kbd> successfull!', 
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
    public function get_subjects(Request $req){
      $class = SubjectMapping::where('class_id', $req->class_id)
                ->join('subjects', 'subjects.id', '=', 'exam_subject_distributions.subject_id')
                ->select('exam_subject_distributions.subject_id as value', 'subjects.name as label')
                ->get();
      return $class;
    }
    public function get_students(Request $req){
      $subject_mapings = SubjectMapping::select('full_mark', 'criteria')
                         ->where('exam_subject_distributions.exam_id', $req->exam_id)
                         ->where('exam_subject_distributions.class_id', $req->class_id)
                         ->where('exam_subject_distributions.subject_id', $req->subject_id)
                         ->join('subjects', 'subjects.id', '=', 'exam_subject_distributions.subject_id')
                         ->select('full_mark', 'criteria', 'subjects.group_id', 'subjects.name')
                         ->first();
      //if(true !== false) abort(404, 'group id: '.$subject_mapings->group_id);
      if(!$subject_mapings) abort(404, 'Exam subject mark distribution not found');  
      
      $students = Student::select('id', 'name', 'roll')
                        ->where('class_id', $req->class_id)
                        ->where(function($query) use($subject_mapings){
                          if($subject_mapings->group_id){
                            $query->where('group_id', $subject_mapings->group_id);
                          }
                        })
                        ->orderBy('roll', 'ASC')->get();
      if(!$students || $students->count() == 0) abort(404, 'Student not found in this class');  
      
      $results = Result::select(['id','total_mark_obtain', 'status', 'result', 'point', 'grade', 'student_id'])
                         ->where('exam_id', $req->exam_id)
                         ->where('class_id', $req->class_id)
                         ->where('subject_id', $req->subject_id)
                         ->get(); 
      $student_results = [];
      
      foreach ($students as $student){
        $temp = [];
        $result_criteria = json_decode($results->where('student_id', $student->id)->first()?->result, true);
        foreach (json_decode($subject_mapings->criteria, true) as $criteria){
          
          $temp[] = [
            "title" => $criteria['title'],
            "short_title" => $criteria['short_title'],
            "full_mark" => $criteria['full_mark'],
            "pass_mark" => $criteria['pass_mark'],
            "mark_obtain" => $this->get_criteria('short_title', $criteria['short_title'], 'mark_obtain', $result_criteria),
            "status" => $this->get_criteria('short_title', $criteria['short_title'], 'status', $result_criteria),
          ];
        }
        $student_results[] = [
          "id" => $results->where('student_id', $student->id)->first()?->id ?? null, // actually result id
          "exam_id" => $req->exam_id,
          "appeared" => ($results?->count() != 0) ? boolval(($results->where('student_id', $student->id)->count())) : true,
          "class_id" => $req->class_id,
          "roll" => $student->roll,
          "student_id" => $student->id,
          "student_name" => $student->name,
          "subject_id" => $req->subject_id,
          "total_mark_obtain" => $results->where('student_id', $student->id)->first()?->total_mark_obtain ?? null,
          "full_mark" => $subject_mapings->full_mark,
          "point" => $results->where('student_id', $student->id)->first()?->point ?? null,
          "grade" => $results->where('student_id', $student->id)->first()?->grade ?? null,
          "status" => $results->where('student_id', $student->id)->first()?->status ?? null,
          "result" => $temp
        ];
      }
      return $student_results;
    }
    
    private function get_criteria($find, $match, $return, $from = []){
      if(!$from) return '';
      foreach ($from as $item){
        if($item[$find] == $match){
          return $item[$return];
        }
      }
      return '';
    }
}
