<?php

namespace App\Http\Controllers\Pdf;
use App\Models\Institute;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Classes;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
      $institute = Institute::orderBy('id', 'desc')->first();
      $exam = Exam::where('id', $request->exam_id)->select('name')->first();
      config([
        'pdf.format' => [210, 148.5],
        'pdf.orientation' => 'L',
        'pdf.margin_top' => '5',
        'pdf.margin_bottom' => '5',
        'pdf.margin_left' => '5',
        'pdf.margin_right' => '5',
      ]);
      return PDF::loadView('pdf.admit-card', ['institute' => $institute, 'students' => $students, 'exam' => $exam])->stream('tt.pdf');
      return view('pdf.admit-card', ['institute' => $institute, 'students' => $students, 'exam' => $exam]);
    }
    
    public function attendance_sheet(Request $request){
      config([
        'pdf.format' => 'A4',
        'pdf.orientation' => 'L',
        'pdf.margin_top' => '50',
        'pdf.margin_bottom' => '5',
        'pdf.margin_left' => '5',
        'pdf.margin_right' => '5',
      ]);
      $institute = Institute::orderBy('id', 'desc')->first();
      $exam = Exam::where('id', $request->exam_id)->first();
      $class = Classes::where('id', $request->class_id)->with('subjects')->first();
      $students = Student::where('class_id', $request->class_id)->get();
      return PDF::loadView('pdf.attendance-sheet', compact('institute', 'exam', 'class', 'students'))->stream('tt.pdf');
      return view('pdf.attendance-sheet', compact('institute', 'exam', 'class', 'students'));
    }
    
    
}
