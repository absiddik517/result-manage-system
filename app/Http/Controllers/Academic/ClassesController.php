<?php

namespace App\Http\Controllers\Academic;

use Exception;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Helper\Traits\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\Classes\StoreRequest;
use App\Http\Requests\Academic\Classes\UpdateRequest;
use App\Http\Resources\Academic\ClassesResource;

class ClassesController extends Controller
{
    use Filter;
    public function index(Request $r){
      $classes = ClassesResource::collection(Classes::with('subjects.group')
                ->select('name', 'short_name', 'id', 'has_group')
                ->where(function($query) use ($r){
                  if($r->has('search')){
                    $query->where('name', 'like', '%'.$r->search.'%')
                          ->orWhere('short_name', 'like', '%'.$r->search.'%');
                  }
                })
                ->paginate($r->per_page ?? '')->withQueryString()
                );
      $params = $this->getParams();
      return inertia('Academic/Class/Index', compact('classes', 'params'));
    }
    
    public function create(){
      $groups = Group::select('name', 'id')->get();
      return inertia('Academic/Class/Create', compact('groups'));
    }
    public function edit($id){
      $class = Classes::where('id', $id)->select('id', 'name', 'short_name', 'has_group')->with('subjects')->first();
      $common = [];
      $group = [];
      if(!$class->subjects->count()){
        $common[] = [
          'subject_id' => '',
          'group_id' => null
        ];
        if($class->has_group){
          $group[] = ['subject_id' => '', 'group_id' => ''];
        }
      }else{
        foreach ($class->subjects->where('group_id', null) as $subject){
          $common[] = [
            'subject_id' => $subject->pivot->subject_id,
            'group_id' => $subject->pivot->group_id,
          ];
        }
        foreach ($class->subjects->whereNotNull('group_id') as $subject){
          $group[] = [
            'subject_id' => $subject->pivot->subject_id,
            'group_id' => $subject->pivot->group_id,
          ];
        }
      }
      $output = [
        'id' => $class->id,
        'name' => $class->name,
        'short_name' => $class->short_name,
        'has_group' => $class->has_group,
        'subjects' => [
          'common' => $common,
          'group' => $group
        ],
      ];
      $form_data = $output;
      $groups = pluckByKey(Group::select('name', 'id')->with('subjects')->get());
      return inertia('Academic/Class/Edit', compact('groups', 'form_data'));
    }
    
    public function store(StoreRequest $req){
      $group_subjects = $req->validated()['subjects']['group'] ?? [];
      $subjects = [...$req->validated()['subjects']['common'], ...$group_subjects];
      try{
        Classes::create([
          "name" => $req->validated()['name'],
          "short_name" => $req->validated()['short_name'],
        ])->class_subjects()->createMany($subjects);
        $toast = [
          'message' => 'Class has <kbd>created</kbd> successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => $e->getMessage(), 
          'type' => 'error'
        ];
      }
      return redirect()->route('classes.index')->with('toast', $toast);
    }
    
    public function update($id, StoreRequest $req){
      $group_subjects = $req->validated()['subjects']['group'] ?? [];
      $subjects = [...$req->validated()['subjects']['common'], ...$group_subjects];
      try{
        $class = Classes::find($id);
        $class->update([
          "name" => $req->validated()['name'],
          "short_name" => $req->validated()['short_name'],
          "has_group" => $req->validated()['has_group'],
        ]);
        $class->class_subjects()->delete();
        $class->class_subjects()->createMany($subjects);
        $toast = [
          'message' => 'Class <strong>'.$class->name.'</strong> has <kbd>updated</kbd> successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => exception_message($e), 
          'type' => 'error'
        ];
      }
      return redirect()->route('classes.index')->with('toast', $toast);
    }
    
    public function destroy($id){
      //sleep(5);
      try{
        $class = Classes::findOrFail($id);
        $class->class_subjects()->delete();
        $class->delete();
        $toast = [
          'message' => 'Class <strong>'.$class->name.'</strong> has <kbd>deleted</kbd> successfull!', 
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
    
    public function get_classes(Request $req){
      $classes = Classes::where(function($query) use($req){
        if($req->has('name')){
          $query->where('name', 'like', '%'.$req->name.'%')
                ->where('short_name', 'like', '%'.$req->name.'%');
        }
      })->where(function($query) use($req){
        if($req->has('short_name')){
          $query->where('short_name', 'like', '%'.$req->short_name.'%');
        }
      })
      ->select('id as value', 'name as label')
      ->get();
      return $classes;
    }
}
