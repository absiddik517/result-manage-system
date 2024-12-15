<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use App\Models\Exam;
use App\Models\SubjectMapping;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Result;
use App\Models\Student;
use App\Models\Institute;

class MarksheetController extends Controller
{
    public function index(Request $req){
      $data = $this->process_result($req);
      return PDF::loadView('pdf.marksheet', $data)->stream('tt.pdf');
    }
    
    public function print_all_marksheet(Request $req){
      $data = $this->process_all_result($req);
      return PDF::loadView('pdf.marksheets', $data)->stream('marksheets.pdf');
      dd($data);
      return view('pdf.marksheets', $data);
    }
    
    public function process_all_result($req){
      $exam = Exam::where('id', $req->exam_id)->select('name')->first();
      $class = Classes::where('id', $req->class_id)->select('name', "has_group")->first();
      !$exam ?? abort(404, "Exam not found");
      $marks_distributions = SubjectMapping::where('exam_subject_distributions.class_id', $req->class_id)
                ->leftJoin('subjects', 'subjects.id', '=', 'exam_subject_distributions.subject_id')
                ->where('exam_subject_distributions.exam_id', $req->exam_id)
                ->select([
                  'exam_subject_distributions.full_mark',
                  'exam_subject_distributions.criteria',
                  'subjects.name', 'subjects.short_name',
                  'subjects.id', 'subjects.group_id'
                ])
                ->orderBy('subjects.id', 'ASC')
                ->get();
      $student_results = Result::join('subjects', 'subjects.id', '=', 'results.subject_id')
                ->join('students', 'students.id', '=', 'results.student_id')
                ->leftJoin('groups', 'groups.id', '=', 'results.group_id')
                ->where('results.exam_id', $req->exam_id)
                ->where('results.class_id', $req->class_id)
                ->select([
                    'results.total_mark_obtain', 'results.point', 'results.grade',
                    'results.status', 'results.result', "results.group_id", 'subjects.name',
                    'subjects.short_name', "students.id as student_id",
                    "students.name as student_name", "students.roll", "students.optional_subject_id",
                    "groups.name as group_name"
                  ])
                  ->get();
      !$student_results ?? abort(404, "Result not found");
      $students = [];
      foreach ($student_results as $result) {
        // Check if the roll number already exists in $students
        if (!array_key_exists($result->roll, $students)) {
          $students[$result->roll] = [
            "id" => $result->student_id,
            "roll" => $result->roll,
            "name" => $result->student_name,
            "optional_subject_id" => $result->optional_subject_id,
            "group_id" => null,
            "group_name" => null,
          ];
        }
    
        // Assign group_id only if it's not null
        if ($result->group_id !== null) {
          $students[$result->roll]['group_id'] = $result->group_id;
          $students[$result->roll]['group_name'] = $result->group_name;
        }
      }
      $allCriteria = [];
      $student_result = [];
      
      foreach ($students as $student){
        $results = $student_results->where('student_id', $student['id']);
        //dd($results->toArray());
        $subjects = [];
        foreach ($marks_distributions as $subject){
          $critera = [];
          if(!$subject->group_id || $subject->group_id == $student['group_id']){
            foreach (json_decode($subject->criteria, true) as $part){
              //dd($this->get_criteria_data($results->where('name', $subject->name)->first(), $part['title'], 'mark_obtain'));
              $critera[$part['title']] = [
                'short_title' => $part['short_title'],
                'full_mark' => $part['full_mark'],
                'pass_mark' => $part['pass_mark'],
                'mark_obtain' => $this->get_criteria_data($results->where('name', $subject->name)->first(), $part['title'], 'mark_obtain'),
                'status' => $this->get_criteria_data($results->where('name', $subject->name)->first(), $part['title'], 'status'),
              ];
              if(!in_array($part['title'], $allCriteria)){
                $allCriteria[] = $part['title'];
              }
            }
            $subjects[$subject->name] = [
              'id' => $subject->id,
              'full_mark' => $subject->full_mark,
              'short_name' => $subject->short_name,
              'total_mark_obtain' => $results->where('name', $subject->name)->first()?->total_mark_obtain ?? 'Ab',
              'grade' => $results->where('name', $subject->name)->first()?->grade ?? 'F',
              'point' => $results->where('name', $subject->name)->first()?->point ?? 0.00,
              'status' => $results->where('name', $subject->name)->first()?->status ?? 0,
              'result' => $critera,
            ];
          }
        }
        //dd($this->calculate_result($subjects, $student));
        $student_result [] = [
          'subjects' => $subjects,
          'student' => $student,
          'result' => $this->calculate_result($subjects, $student)
        ];
      }
      $max_marks = Result::join('subjects', 'subjects.id', '=', 'results.subject_id')
                  ->where('results.class_id', $req->class_id)
                  ->where('results.exam_id', $req->exam_id)
                  ->select('subjects.name')
                  ->selectRaw('MAX(total_mark_obtain) as max')
                  ->groupBy('subjects.name')
                  ->get();
      $max_marks = pluckByKey($max_marks, 'name', true);
      return [
        'theads' => $allCriteria,
        'institute' => Cache::get('institute'),
        'exam' => $exam,
        'class' => $class,
        'students' => $student_result,
        'max' => $max_marks
      ];
    }
    
    private function xc_process_all_result($req){
      $institute = Cache::get('institute');
      $exam = Exam::where('id', $req->exam_id)->select('name')->first();
      $students = Student::where('students.class_id', $req->class_id)
                ->join('classes', 'classes.id', '=', 'students.class_id')
                ->leftJoin('groups', 'groups.id', '=', 'students.group_id')
                ->select('students.id', 'students.name', 'students.roll', 'classes.name as class', 'groups.name as group', 'students.group_id', 'students.optional_subject_id')
                ->orderBy('students.roll', 'asc')
                ->get();
                
      $marks_distributions = SubjectMapping::where('exam_subject_distributions.class_id', $req->class_id)
                ->leftJoin('subjects', 'subjects.id', '=', 'exam_subject_distributions.subject_id')
                ->where('exam_subject_distributions.exam_id', $req->exam_id)
                ->select([
                  'exam_subject_distributions.full_mark',
                  'exam_subject_distributions.criteria',
                  'subjects.name', 'subjects.short_name',
                  'subjects.id', 'subjects.group_id'
                ])
                ->get();
      
      //dd($marks_distributions->toArray());
      $student_result = [];
      $allCriteria = [];
      foreach ($students as $student){
        $results = Result::join('subjects', 'subjects.id', '=', 'results.subject_id')
                  ->where('results.exam_id', $req->exam_id)
                  ->where('results.class_id', $req->class_id)
                  ->where('results.student_id', $student->id)
                  ->select([
                    'results.total_mark_obtain', 'results.point', 'results.grade',
                    'results.status', 'results.result', 'subjects.name',
                    'subjects.short_name'
                  ])
                  ->get();
        
        
        // preparing result format before putting result
        //dd('hello');
        $subjects = [];
        foreach ($marks_distributions as $subject){
          if(!$subject->group_id || $subject->group_id == $student->group_id){
            $critera = [];
            foreach (json_decode($subject->criteria, true) as $part){
              $critera[$part['title']] = [
                'short_title' => $part['short_title'],
                'full_mark' => $part['full_mark'],
                'pass_mark' => $part['pass_mark'],
                'mark_obtain' => $this->get_criteria_data($results->where('name', $subject->name)->first(), $part['title'], 'mark_obtain'),
                'status' => $this->get_criteria_data($results->where('name', $subject->name)->first(), $part['title'], 'status'),
              ];
              if(!in_array($part['title'], $allCriteria)){
                $allCriteria[] = $part['title'];
              }
            }
            $subjects[$subject->name] = [
              'id' => $subject->id,
              'full_mark' => $subject->full_mark,
              'short_name' => $subject->short_name,
              'total_mark_obtain' => $results->where('name', $subject->name)->first()?->total_mark_obtain ?? 'Ab',
              'grade' => $results->where('name', $subject->name)->first()?->grade ?? 'F',
              'point' => $results->where('name', $subject->name)->first()?->point ?? 0.00,
              'status' => $results->where('name', $subject->name)->first()?->status ?? 0,
              'result' => $critera,
            ];
          }
        }
        $student_result [] = [
          'subjects' => $subjects,
          'student' => $student,
          'result' => $this->calculate_result($subjects, $student)
        ];
      }
      $max_marks = Result::join('subjects', 'subjects.id', '=', 'results.subject_id')
                  ->where('results.class_id', $req->class_id)
                  ->where('results.exam_id', $req->exam_id)
                  ->select('subjects.name')
                  ->selectRaw('MAX(total_mark_obtain) as max')
                  ->groupBy('subjects.name')
                  ->get();
      $max_marks = pluckByKey($max_marks, 'name', true);
      return [
        'theads' => $allCriteria,
        'institute' => $institute,
        'exam' => $exam,
        'students' => $student_result,
        'max' => $max_marks
      ];
    }
    
    private function process_result($req){
      $institute = Cache::get('institute');
      $exam = Exam::where('id', $req->exam_id)->select('name')->first();
      $class = Classes::where('id', $req->class_id)->select('name', 'has_group')->first();
      $student = Student::where('students.id', $req->student_id)
                ->leftJoin('classes', 'classes.id', '=', 'students.class_id')
                ->leftJoin('subjects', 'subjects.id', '=', 'students.optional_subject_id')
                ->leftJoin('groups', 'groups.id', '=', 'students.group_id')
                ->select('students.name', 'students.roll', 'classes.name as class', 'students.group_id', 'students.optional_subject_id', 'subjects.name as optional', 'groups.name as group')
                ->first();
                
      $marks_distributions = SubjectMapping::where('exam_subject_distributions.class_id', $req->class_id)
                ->join('subjects', 'subjects.id', '=', 'exam_subject_distributions.subject_id')
                ->where('exam_subject_distributions.exam_id', $req->exam_id)
                ->where('subjects.group_id', null)
                ->orWhere(function($query) use ($student, $class){
                  if($class->has_group && $student->group_id){
                    $query->orWhere('subjects.group_id', $student->group_id);
                  }
                })
                ->select([
                  'exam_subject_distributions.full_mark',
                  'exam_subject_distributions.criteria',
                  'subjects.name', 'subjects.short_name', 'subjects.id'
                ])
                ->get();
      
      $results = Result::join('subjects', 'subjects.id', '=', 'results.subject_id')
                ->where('results.exam_id', $req->exam_id)
                ->where('results.class_id', $req->class_id)
                ->where('results.student_id', $req->student_id)
                ->select([
                  'results.total_mark_obtain', 'results.point', 'results.grade',
                  'results.status', 'results.result', 'subjects.name',
                  'subjects.short_name'
                ])
                ->get();
      $max_marks = Result::join('subjects', 'subjects.id', '=', 'results.subject_id')
                  ->where('results.class_id', $req->class_id)
                  ->where('results.exam_id', $req->exam_id)
                  ->select('subjects.name')
                  ->selectRaw('MAX(total_mark_obtain) as max')
                  ->groupBy('subjects.name')
                  ->get();
      $max_marks = pluckByKey($max_marks, 'name', true);
      // preparing result format before putting result
      $subjects = [];
      $allCriteria = [];
      foreach ($marks_distributions as $subject){
        $critera = [];
        foreach (json_decode($subject->criteria, true) as $part){
          //dd(json_decode($results->where('name',  $subject->name)->first()->result, true));
          $critera[$part['title']] = [
            'short_title' => $part['short_title'],
            'full_mark' => $part['full_mark'],
            'pass_mark' => $part['pass_mark'],
            'mark_obtain' => $this->get_criteria_data($results->where('name', $subject->name)->first(), $part['title'], 'mark_obtain'),
            'status' => $this->get_criteria_data($results->where('name', $subject->name)->first(), $part['title'], 'status'),
          ];
          if(!in_array($part['title'], $allCriteria)){
            $allCriteria[] = $part['title'];
          }
        }
        $subjects[$subject->name] = [
          'id' => $subject->id,
          'full_mark' => $subject->full_mark,
          'short_name' => $subject->short_name,
          'total_mark_obtain' => $results->where('name', $subject->name)->first()?->total_mark_obtain ?? 'Ab',
          'grade' => $results->where('name', $subject->name)->first()?->grade ?? 'F',
          'point' => $results->where('name', $subject->name)->first()?->point ?? 0.00,
          'status' => $results->where('name', $subject->name)->first()?->status ?? 0,
          'result' => $critera,
        ];
      }
      return [
        'subjects' => $subjects,
        'theads' => $allCriteria,
        'student' => $student,
        'institute' => $institute,
        'exam' => $exam,
        'class' => $class,
        'result' => $this->calculate_result($subjects, $student),
        'max' => $max_marks
      ];
    }
    
    private function get_criteria_data($row, String $match, String $query){
      if(!$row) return 'Ab';
      $results = json_decode($row->result, true);
      foreach ($results as $result){
        if($result['title'] == $match){
          return $result[$query];
        }
      }
      return 0;
    }
    
    private function calculate_result(Array $results, $student){
      $total_mark = 0;
      $total_points = 0;
      $is_passed = 1;
      $total_full_mark = 0;
      foreach ($results as $result){
        $total_mark += intval($result['total_mark_obtain']);
        $total_full_mark += intval($result['full_mark']);
        $is_passed *= ($student['group_id'] && $student['optional_subject_id'] == $result['id']) ? 1 : $result['status'];
        if($result['id'] == $student['optional_subject_id']){
          $total_points += (intval($result['point']) >= 2) ? intval($result['point']) - 2 : 0;
        }else{
          $total_points += intval($result['point']);
        }
      }
      $point = 0;
      $subjects = ($student['group_id']) ? count($results) - 1 : count($results);
      if($is_passed){
        $temp = $total_points/$subjects;
        $point = $temp <= 5 ? $temp : 5.00;
      }
      $output = [
        'total_full_mark' => $total_full_mark,
        'total_marks' => $total_mark,
        'point' => $point,
        'total_point' => $total_points,
        'percent' => ($total_mark*100)/$total_full_mark,
        'grade' => $this->calculate_point($point),
        'roll' => $student['roll'],
        'subjects' => $subjects
      ];
      //dd($output);
      
      return $output;
    }
    
    private function calculate_point($point){
      if(!is_numeric($point)) return 'F';
      if($point == 5){
        return 'A+';
      }else if($point >= 4){
        return 'A';
      }else if($point >= 3.5){
        return 'A-';
      }else if($point >= 3){
        return 'B';
      }else if($point >= 2){
        return 'C';
      }else if($point >= 1){
        return 'D';
      }else{
        return 'F';
      }
    }
}
