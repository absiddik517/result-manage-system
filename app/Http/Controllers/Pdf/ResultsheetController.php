<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use App\Models\Exam;
use App\Models\SubjectMapping;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Result;
use App\Models\Student;
use App\Models\Institute;

class ResultsheetController extends Controller
{
    public function index(Request $req){
      if(!$req->class_id || !$req->exam_id) return back()->with('toast', ['type'=> 'error', 'message' => 'Invalid url!']);
      config([
        'pdf.format' => 'Legal',
        'pdf.orientation' => 'L',
        'pdf.margin_top' => '10',
        'pdf.margin_bottom' => '10',
        'pdf.margin_left' => '10',
        'pdf.margin_right' => '10',
      ]);
      $data = $this->process_results($req);
      //dd($data);
      return PDF::loadView('pdf.resultsheet', $data)->stream('resultsheet.pdf');
      return view('pdf.resultsheet', $data);
    }
    
    private function process_results($req){
      $institute = Institute::select('name', 'address', 'established_at')
                  ->addSelect([
                    'exam_name' => \DB::table('exams')->where('id', $req->exam_id)->select('name'),
                    'class_name' => \DB::table('classes')->where('id', $req->class_id)->select('name'),
                    'has_group' => \DB::table('classes')->where('id', $req->class_id)->select('has_group'),
                  ])
                  ->first();
      if(!$institute) abort(403, 'Institute not found, Set it first.');
      if($institute->has_group){
        $data = $this->process_group_result($req);
      }else{
        $data = $this->process_student_result($req);
      }
      return [
        "institute" => $institute,
        "groups" => $data
      ];
    }
    
    private function process_group_result($req){
      $result = [];
      $groups = Group::select('id', 'name')->get();
      $mappings = SubjectMapping::where('exam_subject_distributions.class_id', $req->class_id)
                ->where('exam_subject_distributions.exam_id', $req->exam_id)
                ->join('subjects', 'subjects.id', '=', 'exam_subject_distributions.subject_id')
                ->select([
                  'exam_subject_distributions.full_mark',
                  'exam_subject_distributions.subject_id',
                  'exam_subject_distributions.criteria', 'subjects.name',
                  'subjects.short_name', 'subjects.group_id'
                ])
                ->get();
      if($mappings->count() == 0) abort(403, 'Subject mapping not found for this class');
      $students = Student::where('students.class_id', $req->class_id)
                  ->select('name', 'roll', 'id', 'group_id', 'optional_subject_id')
                  ->orderBy('students.id', 'asc')
                  ->get();
                  
      if($students->count() == 0) abort(403, 'Student not found for this class');
      $results = Result::where('exam_id', $req->exam_id)
                  ->where('class_id', $req->class_id)
                  ->select([
                    'subject_id', 'student_id', 'total_mark_obtain', 'point',
                    'grade', 'status', 'result'
                  ])
                  ->get();
      if($results->count() == 0) abort(403, 'Result not found for this class');
      $max_criteria = [];
      $group_result = [];
      foreach ($groups as $group){
        $subjects = [];
        foreach ($mappings->whereIn('group_id', [null, $group->id]) as $map){
          $temp_criteria = [];
          foreach (json_decode($map->criteria, true) as $criteria){
            if(!in_array($criteria['short_title'], $max_criteria)) $max_criteria[] = $criteria['short_title'];
            $temp_criteria[$criteria['short_title']] = [
              'title' => $criteria['title'],
              'full_mark' => $criteria['full_mark'],
              'pass_mark' => $criteria['pass_mark'],
            ];
          }
          $subjects[$map->short_name] = [
            'id' => $map->subject_id,
            'name' => $map->name,
            'full_mark' => $map->full_mark,
            'criteria' => $temp_criteria,
          ];
        }
        
        $students_array = [];
        foreach ($students->where('group_id', $group->id) as $student){
          $temp_subject_result = [];
          foreach ($subjects as $subject_name => $subject){
            $student_result = $results->where('student_id', $student->id)->where('subject_id', $subject['id'])->first();
            if($student_result){
              $result_criteria = json_decode($student_result->result, true);
              $temp_subject_result[$subject_name] = [
                ...$subjects[$subject_name],
                'total_mark_obtain' => $student_result->total_mark_obtain,
                'status' => $student_result->status,
                'grade' => $student_result->grade,
                'point' => $student_result->point,
              ];
              
              foreach ($temp_subject_result[$subject_name]['criteria'] as $short_title => $item){
                $temp_subject_result[$subject_name]['criteria'][$short_title]['mark_obtain'] = $this->get_result_parts('short_title', $short_title, 'mark_obtain', $result_criteria);
                $temp_subject_result[$subject_name]['criteria'][$short_title]['status'] = $this->get_result_parts('short_title', $short_title, 'status', $result_criteria);
              }
            }else{
              $temp_subject_result[$subject_name] = [
                ...$subjects[$subject_name],
                'total_mark_obtain' => 'Ab',
                'status' => 0,
                'grade' => 'F',
                'point' => 0,
              ];
              foreach ($subjects[$subject_name]['criteria'] as $short_title => $k){
                $temp_subject_result[$subject_name]['criteria'][$short_title]['mark_obtain'] = 'Ab';
                $temp_subject_result[$subject_name]['criteria'][$short_title]['status'] = 0;
              }
            }
          }
          $students_array[] = [
            'roll' => $student->roll,
            'name' => $student->name,
            'result' => $temp_subject_result,
            ...$this->calculate_result($temp_subject_result, $student)
          ];
        }
        $group_result[$group->name] = [
          "students" => $students_array,
          "subjects" => $subjects,
          "criteria" => $max_criteria
        ];
      }
      return array_filter($group_result, function($students){
        return !empty($students['students']);
      });
    }
    
    private function process_student_result($req){
      $mappings = SubjectMapping::where('exam_subject_distributions.class_id', $req->class_id)
                ->where('exam_subject_distributions.exam_id', $req->exam_id)
                ->join('subjects', 'subjects.id', '=', 'exam_subject_distributions.subject_id')
                ->select([
                  'exam_subject_distributions.full_mark',
                  'exam_subject_distributions.subject_id',
                  'exam_subject_distributions.criteria', 'subjects.name',
                  'subjects.short_name'
                ])
                ->get();
      if($mappings->count() == 0) abort(403, 'Subject mapping not found for this class');
      $students = Student::where('students.class_id', $req->class_id)
                  ->select('name', 'roll', 'id', 'group_id', 'optional_subject_id')
                  ->orderBy('students.id', 'asc')
                  ->get();
                  
      if($students->count() == 0) abort(403, 'Student not found for this class');
      $results = Result::where('exam_id', $req->exam_id)
                  ->where('class_id', $req->class_id)
                  ->select([
                    'subject_id', 'student_id', 'total_mark_obtain', 'point',
                    'grade', 'status', 'result'
                  ])
                  ->get();
      if($results->count() == 0) abort(403, 'Result not found for this class');
      
      // create a subject array with criteria
      $subjects = [];
      $max_criteria = [];
      foreach ($mappings as $map){
        $temp_criteria = [];
        foreach (json_decode($map->criteria, true) as $criteria){
          if(!in_array($criteria['short_title'], $max_criteria)) $max_criteria[] = $criteria['short_title'];
          $temp_criteria[$criteria['short_title']] = [
            'title' => $criteria['title'],
            'full_mark' => $criteria['full_mark'],
            'pass_mark' => $criteria['pass_mark'],
          ];
        }
        $subjects[$map->short_name] = [
          'id' => $map->subject_id,
          'name' => $map->name,
          'full_mark' => $map->full_mark,
          'criteria' => $temp_criteria,
        ];
      }
      //dd($subjects);
      $students_array = [];
      foreach ($students as $student){
        $temp_subject_result = [];
        foreach ($subjects as $subject_name => $subject){
          $student_result = $results->where('student_id', $student->id)->where('subject_id', $subject['id'])->first();
          if($student_result){
            $result_criteria = json_decode($student_result->result, true);
            $temp_subject_result[$subject_name] = [
              ...$subjects[$subject_name],
              'total_mark_obtain' => $student_result->total_mark_obtain,
              'status' => $student_result->status,
              'grade' => $student_result->grade,
              'point' => $student_result->point,
            ];
            
            foreach ($temp_subject_result[$subject_name]['criteria'] as $short_title => $item){
              $temp_subject_result[$subject_name]['criteria'][$short_title]['mark_obtain'] = $this->get_result_parts('short_title', $short_title, 'mark_obtain', $result_criteria);
              $temp_subject_result[$subject_name]['criteria'][$short_title]['status'] = $this->get_result_parts('short_title', $short_title, 'status', $result_criteria);
            }
          }else{
            $temp_subject_result[$subject_name] = [
              ...$subjects[$subject_name],
              'total_mark_obtain' => 'Ab',
              'status' => 0,
              'grade' => 'F',
              'point' => 0,
            ];
            foreach ($subjects[$subject_name]['criteria'] as $short_title => $k){
              $temp_subject_result[$subject_name]['criteria'][$short_title]['mark_obtain'] = 'Ab';
              $temp_subject_result[$subject_name]['criteria'][$short_title]['status'] = 0;
            }
          }
        }
        $students_array[] = [
          'roll' => $student->roll,
          'name' => $student->name,
          'result' => $temp_subject_result,
          ...$this->calculate_result($temp_subject_result, $student)
        ];
      }
      
      return[
          "NO_GROUP" => [
            'students' => $students_array,
            'criteria' => $max_criteria,
            'subjects' => $subjects,
          ]
      ]; 
    }
    
    private function get_result_parts(String $match = '', String $with = '', String $query = '', Array $records){
      if(!$match || !$query || !$with) return '';
      foreach ($records as $record){
        if($record[$match] == $with){
          return $record[$query];
        }
      }
    }
    
    private function calculate_result(Array $subjects, $student){
      //dd($student);
      $grade = 'F';
      $point = 0;
      $is_passed = 1;
      $total_number = 0;
      $total_point = 0;
      foreach ($subjects as $subject){
        $total_number += intval($subject['total_mark_obtain']);
        if($student->group_id){
          if($subject['id'] == $student->optional_subject_id){
            $is_passed *= 1;
            $total_point += (intval($subject['point']) >= 2) ? intval($subject['point']) - 2 : intval($subject['point']);
          }else{
            $total_point += intval($subject['point']);
            $is_passed *= intval($subject['status']);
          }
        }else{
          $is_passed *= intval($subject['status']);
          $total_point += intval($subject['point']);
        }
      }
      if($is_passed){
        $total_subjects = ($student->group_id) ? count($subjects) - 1 : count($subjects);
        $point = round($total_point/$total_subjects, 2);
        $grade = $this->calculate_point(($point <= 5) ? $point : 5);
      }
      
      return [
        'total' => $total_number,
        'grade' => $grade,
        'point' => ($point <= 5) ? $point : 5
      ];
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
