<?php

namespace App\Http\Controllers\Academic;

use Exception;
use App\Models\Subject;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Helper\Traits\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\Subject\StoreRequest;
use App\Http\Requests\Academic\Subject\UpdateRequest;
use App\Http\Resources\Academic\SubjectResource;

class SubjectController extends Controller
{
    use Filter;
    public function index(Request $req){
      $classes = Classes::select('id', 'name')->get();
      $subjects = SubjectResource::collection(
        Subject::leftJoin('groups', 'groups.id', '=', 'subjects.group_id')
            ->select('subjects.id', 'subjects.name', 'subjects.short_name', 'groups.name as group')
            ->when($req->search, function($query) use($req){
              $query->orWhere('subjects.name', 'like', '%'.$req->search.'%')
                  ->orWhere('subjects.short_name', 'like', '%'.$req->search.'%')
                  ->orWhere('groups.name', 'like', '%'.$req->search.'%');
            })
            ->orderBy('subjects.id', $req->orderDirection ?? 'asc')
            ->paginate()->withQueryString()
      );
      $params = request()->input();
      return inertia('Academic/Subject', compact('subjects', 'params', 'classes'));
    }
    
    public function store(StoreRequest $req){
      try{
        Subject::create($req->validated());
        $toast = [
          'message' => 'Subject has <kbd>created</kbd> successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => $e->getMessage(), 
          'type' => 'error'
        ];
      }
      return redirect()->route('subject.index')->with('toast', $toast);
    }
    
    public function update($id, UpdateRequest $req){
      try{
        $subject = Subject::find($id);
        $subject->update($req->validated());
        $toast = [
          'message' => 'Subject <strong>'.$subject->name.'</strong> has <kbd>updated</kbd> successful!', 
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
        $subject = Subject::findOrFail($id);
        $subject->delete();
        $toast = [
          'message' => 'Subject <strong>'.$subject->name.'</strong> has <kbd>deleted</kbd> successfull!', 
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
    
    public function subject_get_select(Request $req){
      $subjects = Subject::where(function($q) use($req){
                      if($req->has('name')){
                        $q->where('subjects.name', 'like', '%'.$req->name.'%');
                      }
                      if($req->has('group_id')){
                        $q->where('group_id', ($req->group_id==0) ? null : $req->group_id);
                      }
                    })
                    ->leftJoin('groups', 'subjects.group_id', '=', 'groups.id')
                    ->select('subjects.name as label', 'subjects.id as value', 'groups.name as group')
                    ->orderBy('subjects.id', 'asc')
                    ->get();
      $result = [];
      foreach ($subjects as $subject){
        $label = $subject->label;
        if($subject->group){
          $label = $subject->label.' ('. $subject->group.')';
        }
        $result[] = [
          'label' => $label,
          'value' => $subject->value,
        ];
      }
      return $result;
    }
}
