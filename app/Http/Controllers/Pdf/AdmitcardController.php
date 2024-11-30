<?php

namespace App\Http\Controllers\Pdf;
use App\Models\Institute;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Classes;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdmitcardController extends Controller
{
    public function index(Request $request){
      abort_if(!$request->class_id || !$request->exam_id, 403, 'Bad Url');
      request()->validate([
        'class_id' => 'required',
        'exam_id' => 'required',
      ]);
      $students = Student::where('class_id', $request->class_id)
                  ->join('classes', 'students.class_id', '=', 'classes.id')
                  ->leftJoin('groups', 'students.group_id', '=', 'groups.id')
                  ->select([
                    'students.*', 'classes.name as class_name', 'groups.name as group_name'
                  ])
                  ->get();
      $institute = Cache::get('institute');
      $exam = Exam::where('id', $request->exam_id)->select('name')->first();
      $format = [
        "simple" => [
          "format" => "A4",
          "margin_top" => "0",
          "margin_bottom" => "0",
          "margin_left" => "0",
          "margin_right" => "0",
          "show_watermark_image" => false
        ],
        "with-subjects" => [
          "format" => [148.5, 210],
          "margin_top" => "5",
          "margin_bottom" => "5",
          "margin_left" => "5",
          "margin_right" => "5",
          "show_watermark_image" => true
        ],
      ];
      $template = Cache::get('admit_template');
      config([
        'pdf.format' => $format[$template]['format'],
        'pdf.orientation' => 'P',
        'pdf.margin_top' => $format[$template]['margin_top'],
        'pdf.margin_bottom' => $format[$template]['margin_bottom'],
        'pdf.margin_left' => $format[$template]['margin_left'],
        'pdf.margin_right' => $format[$template]['margin_right'],
        'pdf.show_watermark_image' => $format[$template]['show_watermark_image'],
      ]);
      return PDF::loadView('pdf.admits.'.$template, ['institute' => $institute, 'students' => $students, 'exam' => $exam])->stream('tt.pdf');
      return view('pdf.admits.simple', ['institute' => $institute, 'students' => $students, 'exam' => $exam]);
    }
    
    public function attendance_sheet(Request $request){
      config([
        'pdf.format' => $request->paper_size ?? "A4",
        'pdf.orientation' => 'L',
        'pdf.margin_top' => '5',
        'pdf.margin_bottom' => '5',
        'pdf.margin_left' => '15',
        'pdf.margin_right' => '5',
      ]);
      $institute = Cache::get('institute');
      $exam = Exam::where('id', $request->exam_id)->first();
      $class = Classes::where('id', $request->class_id)->with('subjects')->first();
      $students = Student::where('class_id', $request->class_id)->get();
      return PDF::loadView('pdf.attendance-sheet', compact('institute', 'exam', 'class', 'students'))->stream('tt.pdf');
      return view('pdf.attendance-sheet', compact('institute', 'exam', 'class', 'students'));
    }
    
    
}
