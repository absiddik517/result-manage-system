<?php

namespace App\Http\Controllers\Academic;

use Exception;
use App\Models\Institute;
use Illuminate\Http\Request;
use App\Helper\Traits\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\Institute\StoreRequest;
use App\Http\Requests\Academic\Institute\UpdateRequest;
use App\Http\Resources\Academic\InstituteResource;

class InstituteController extends Controller
{
    use Filter;
    public function index(){
      $institutes = InstituteResource::collection($this->getFilterData(Institute::class, [
        'like' => ["language", "name", "established_at", "address", "logo"]
      ])->withQueryString());
      $params = $this->getParams();
      return inertia('Academic/Institute', compact('institutes', 'params'));
    }
    
    public function store(StoreRequest $req){
      try{
        Institute::create($req->validated());
        $toast = [
          'message' => 'Institute has <kbd>created</kbd> successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => $e->getMessage(), 
          'type' => 'error'
        ];
      }
      return redirect()->route('institute.index')->with('toast', $toast);
    }
    
    public function update($id, UpdateRequest $req){
      try{
        $institute = Institute::find($id);
        $institute->update($req->validated());
        $toast = [
          'message' => 'Institute <strong>'.$institute->name.'</strong> has <kbd>updated</kbd> successful!', 
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
        $institute = Institute::findOrFail($id);
        $institute->delete();
        $toast = [
          'message' => 'Institute <strong>'.$institute->name.'</strong> has <kbd>deleted</kbd> successfull!', 
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
}
