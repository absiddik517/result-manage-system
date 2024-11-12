<?php

namespace App\Http\Controllers\Academic;

use Exception;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Helper\Traits\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\Exam\StoreRequest;
use App\Http\Requests\Academic\Exam\UpdateRequest;
use App\Http\Resources\Academic\ExamResource;

class ExamController extends Controller
{
    use Filter;
    public function index(){
      $exams = ExamResource::collection($this->getFilterData(Exam::class, [
        'like' => ["name", "short_name"]
      ])->withQueryString());
      $params = $this->getParams();
      return inertia('Academic/Exam', compact('exams', 'params'));
    }
    
    public function store(StoreRequest $req){
      try{
        Exam::create($req->validated());
        $toast = [
          'message' => 'Exam has <kbd>created</kbd> successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => $e->getMessage(), 
          'type' => 'error'
        ];
      }
      return redirect()->route('exam.index')->with('toast', $toast);
    }
    
    public function update($id, UpdateRequest $req){
      try{
        $exam = Exam::find($id);
        $exam->update($req->validated());
        $toast = [
          'message' => 'Exam <strong>'.$exam->name.'</strong> has <kbd>updated</kbd> successful!', 
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
        $exam = Exam::findOrFail($id);
        $exam->delete();
        $toast = [
          'message' => 'Exam <strong>'.$exam->name.'</strong> has <kbd>deleted</kbd> successfull!', 
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
    
    public function get_exams(Request $req){
      $exams = Exam::where(function($query) use($req){
        if($req->has('name')){
          $query->where('name', 'like', '%'.$req->name.'%');
        }
      })
      ->select('id as value', 'name as label')
      ->get();
      return $exams;
    }
}
