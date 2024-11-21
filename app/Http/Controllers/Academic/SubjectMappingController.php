<?php

namespace App\Http\Controllers\Academic;

use DB;
use Exception;
use App\Models\Exam;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\SubjectMapping;
use Illuminate\Http\Request;
use App\Helper\Traits\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\SubjectMapping\StoreRequest;
use App\Http\Requests\Academic\SubjectMapping\UpdateRequest;
use App\Http\Resources\Academic\SubjectMappingResource;

class SubjectMappingController extends Controller
{
    use Filter;
    public function index(Request $req){
      $exams = Exam::with('mappings')
              ->where(function($q) use ($req) {
                  if ($req->has('search')) {
                      $q->where('name', 'like', '%' . $req->search . '%');
                  }
              })
              ->get();
      $classes = Classes::select('id', 'name')
                  ->get();
      $data = [];
      foreach ($exams as $exam){
        foreach ($classes as $class){
          $subjects = $exam->mappings?->where('class_id', $class->id);
          $tempsub = [];
          if($subjects->count() > 0){
            foreach ($subjects as $subject){
              $tempsub[$subject->subject->name] = [
                'full_mark' => $subject->full_mark,
                'criteria' => json_decode($subject->criteria, true),
              ];
            }
            $data[] = [
              'exam' => $exam->name,
              'class' => $class->name,
              'subjects' => $tempsub,
            ];
          }
        }
      }
      $subjectmappings = $data;
      $params = $this->getParams();
      return inertia('Academic/Mapping/Index', compact('subjectmappings',
      'params'));
    }
    
    public function create(){
      $exams = Exam::where('isCompleted', false)->select(['id as value', 'name as label'])->get();
      $classes = Classes::select(['id as value', 'name as label'])->get();
      return inertia('Academic/Mapping/Create', compact('exams', 'classes'));
    }
    
    public function store(StoreRequest $req){
      //dd($req->validated());
      try{
        $created = 0;
        $updated = 0;
        $exam_id = null;
        $class_id = null;
        foreach ($req->validated()['mappings'] as $item){
          if($item['id']){
            SubjectMapping::find($item['id'])->update([
              'exam_id' => $item['exam_id'],
              'subject_id' => $item['subject_id'],
              'class_id' => $item['class_id'],
              'full_mark' => $item['full_mark'],
              'criteria' => json_encode($item['criteria'] ?? ''),
            ]);
            $updated++;
          }else{
            SubjectMapping::create([
              'exam_id' => $item['exam_id'],
              'subject_id' => $item['subject_id'],
              'class_id' => $item['class_id'],
              'full_mark' => $item['full_mark'],
              'criteria' => json_encode($item['criteria'] ?? ''),
            ]);
            $created++;
          }
          $exam_id = $item['exam_id'];
          $class_id = $item['class_id'];
        }
        $toast = [
          'message' => 'Operation successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => $e->getMessage(), 
          'type' => 'error'
        ];
      }
      return redirect()->route('exam.map.create')->with([
        'toast' => $toast,
        'exam_id' => $exam_id,
        'class_id' => $class_id,
      ]);
    }
    
    public function get_exams(){
      $exams = Exam::select(['id', 'name'])->get();
      $classes = Classes::select(['id', 'name'])->get();
      $response = [
        'exams' => $exams,
        'classes' => $classes,
      ];
      return $response;
    }
    public function get_subjects($class_id){
      $exams = Subject::select(['id', 'name'])->where('class_id', $class_id)->get();
      return $exams;
    }
    
    public function update($id, UpdateRequest $req){
      try{
        $subjectmapping = SubjectMapping::find($id);
        $subjectmapping->update($req->validated());
        $toast = [
          'message' => 'SubjectMapping <strong>'.$subjectmapping->name.'</strong> has <kbd>updated</kbd> successful!', 
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
        $subjectmapping = SubjectMapping::findOrFail($id);
        $subjectmapping->delete();
        $toast = [
          'message' => 'SubjectMapping <strong>'.$subjectmapping->name.'</strong> has <kbd>deleted</kbd> successfull!', 
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
    
    public function prepareForm(Request $req){
      $class = Classes::where('id', $req->class_id)->with('subjects')->select('id', 'name')->first();
      if($class->subjects->count() === 0) abort(404, 'Subject not found in this class.');
      $exams = SubjectMapping::where('class_id', $req->class_id)
        ->where('exam_id', '!=', $req->exam_id)
        ->join('exams', 'exams.id', '=', 'exam_subject_distributions.exam_id')
        ->orderBy('exam_id', 'desc')
        ->select('exam_id', 'name')->groupBy('exam_id', 'name')->get();
      
      $exam_maps = SubjectMapping::where('class_id', $req->class_id)
        ->join('exams', 'exams.id', '=', 'exam_subject_distributions.exam_id')
        ->select('exam_subject_distributions.id', 'exam_id', 'subject_id', 'criteria', 'full_mark', 'exams.name as exam')
        ->get();
      $previous_exams = [];
      foreach ($exams as $exam){
        $maps = $exam_maps->where('exam_id', $exam->exam_id);
        $previous_exams[$exam->name] = $this->prepareMappings($class->subjects, $exam_maps->where('exam_id', $exam->exam_id), $req, true);
      }
      return [
        'previous' => $exam_maps->where('exam_id', $req->exam_id)->count() === 0 ? $previous_exams : [],
        'mappings' => $this->prepareMappings($class->subjects, $exam_maps->where('exam_id', $req->exam_id), $req)
      ];
    }
    
    private function prepareMappings($subjects, $exam_maps, $req, $isPrevious = false){
      $output = [];
      foreach ($subjects as $sub){
        $output[$sub->id] = [
          'id' => null,
          'exam_id' => $req->exam_id,
          'class_id' => $req->class_id,
          'subject_id' => $sub->id,
          'name' => $sub->name,
          'full_mark' => '',
          'criteria' => []
        ];
      }
      foreach ($exam_maps as $map){
        $output[$map->subject_id]['criteria'] = json_decode($map->criteria);
        $output[$map->subject_id]['id'] = $isPrevious ? null :$map->id;
        $output[$map->subject_id]['full_mark'] = $map->full_mark;
      }
      
      return $output;
    }
}
