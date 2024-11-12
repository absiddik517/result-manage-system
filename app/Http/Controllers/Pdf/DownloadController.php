<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use App\Models\Exam;
use App\Models\SubjectMapping;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Institute;

class DownloadController extends Controller
{
    public function result_entry_sheet(Request $req){
      config([
        'pdf.format' => 'A4',
        'pdf.orientation' => 'P',
        'pdf.margin_top' => '10',
        'pdf.margin_bottom' => '10',
        'pdf.margin_left' => '10',
        'pdf.margin_right' => '10',
      ]);
      $data = $this->get_result_entry_data($req);
      //dd($data);
      return PDF::loadView('pdf.result-entry-from', $data)->stream('result_form.pdf');
      return view('pdf.result-entry-from', $data);
    }
    public function new_exam_plan($exam_id){
      config([
        'pdf.format' => 'A4',
        'pdf.orientation' => 'P',
        'pdf.margin_top' => '5',
        'pdf.margin_bottom' => '5',
        'pdf.margin_left' => '5',
        'pdf.margin_right' => '5',
      ]);
      $data = $this->get_exam_plan_data($exam_id);
      return PDF::loadView('pdf.new-exam', $data)->stream('result_form.pdf');
      return view('pdf.new-exam', $data);
    }
    
    private function get_exam_plan_data($exam_id){
      $exam = Exam::where('id', $exam_id)->select('name', 'id')->first();
      if(!$exam) abort(404, 'Exam not found.');
      $classes = Classes::with('subjects')->select('id', 'short_name as name')->get();
      if(!$classes) abort(404, 'Classes not found.');
      
      $output = [];
      foreach ($classes as $class){
        $tempsub = [];
        foreach ($class->subjects as $subject){
          $tempsub[] = $subject->name;
        }
        $output[] = [
          'name' => $class->name,
          'subjects' => $tempsub,
        ];
      }
      return [
        'exam' => $exam->name,
        'classes' => $output
      ];
    }
    private function get_result_entry_data($req){
      $institute = Institute::orderBy('id', 'DESC')
                  ->select('name', 'address', 'established_at')
                  ->addSelect([
                    'exam_name' => \DB::table('exams')->where('id', $req->exam_id)->selectRaw('name')
                  ])
                  ->first();
      if(!$institute) abort(404, 'Institute not found');
      $mappings = SubjectMapping::where('exam_id', $req->exam_id)->get();
      if($mappings->count() === 0) abort(403, 'Subject mapping not found for this exam');
      $classes = Classes::join('exam_subject_distributions', 'classes.id', '=', 'exam_subject_distributions.class_id')
                  ->with('subjects', 'students')
                  ->select('classes.id', 'classes.name')
                  ->groupBy('classes.id', 'classes.name')
                  ->havingRaw('COUNT(exam_subject_distributions.id) > 0')
                  ->get();
      
      $data = [];
      foreach ($classes as $class){
        $temp_st = [];
        $temp_sub = [];
        foreach ($class->subjects as $subject){
          if($mappings->where('class_id', $class->id)->where('subject_id', $subject->id)->count() === 1){
            $map = json_decode($mappings->where('class_id', $class->id)->where('subject_id', $subject->id)->first()?->criteria, true);
            $temp_sub[$subject->name] = $map;
          }
        }
        foreach ($class->students as $student){
          $temp_st[] = [
            'name' => $student->name,
            'roll' => $student->roll
          ];
        }
        $data[] = [
          'class_name' => $class->name,
          'students' => $temp_st,
          'subjects' => $temp_sub
        ];
      }
      
      return [
        'classes' => $data,
        'institute' => $institute
      ];
    }
    
    
}
