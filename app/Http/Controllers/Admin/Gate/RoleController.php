<?php

namespace App\Http\Controllers\Admin\Gate;

use Exception;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use App\Helper\Traits\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Resources\Gate\RoleResource;

class RoleController extends Controller
{
    use Filter;
    public function index(){
      $roles = RoleResource::collection($this->getFilterData(Role::class, [
        'like' => ['name', 'guard_name']
      ])->withQueryString());
      $params = $this->getParams();
      return inertia('Admins/Gate/Role', compact('roles', 'params'));
    }
    
    public function store(RoleStoreRequest $req){
      try{
        Role::create($req->validated());
        $toast = [
          'message' => 'Role create successful', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $toast = [
          'message' => $e->getMessage(), 
          'type' => 'error'
        ];
      }
      return redirect()->route('admin.gate.role.index')->with('toast', $toast);
    }
    
    public function update($id, RoleUpdateRequest $req){
      try{
        $role = Role::find($id);
        $role->update($req->validated());
        $toast = [
          'message' => 'Role <strong>'.$role->name.'</strong> has updated successful!', 
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
        $role = Role::findOrFail($id);
        $role->delete();
        $toast = [
          'message' => 'Role <strong>'.$role->name.'</strong> has deleted successfull!', 
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
