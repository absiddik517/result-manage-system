<?php

namespace App\Http\Controllers\Gate;

use Exception;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\Traits\Filter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gate\Permission\StoreRequest;
use App\Http\Requests\Gate\Permission\UpdateRequest;
use App\Http\Resources\Gate\PermissionResource;

class PermissionController extends Controller
{
    use Filter;
    public function index(){
      $permissions = PermissionResource::collection($this->getFilterData(Permission::class, [
        'like' => ["name", "guard_name", "group_name"]
      ])->withQueryString());
      $params = $this->getParams();
      return inertia('Gate/Permission', compact('permissions', 'params'));
    }
    
    public function store(StoreRequest $req){
      if(!gate_allows('create_permission')) abort(403, 'Unauthorized action');
      $exceptions = [];
      try{
        foreach ($req->permissions as $permission) {
          Permission::create([
            'name' => $permission.'_'.strtolower(str_replace(' ', '_', $req->model)),
            'group_name' => $req->model,
            'guard_name' => $req->guard_name,
          ]);
        }
        $toast = [
          'message' => 'Permission has <kbd>created</kbd> successful!', 
          'type' => 'success'
        ];
      } catch (Exception $e) {
        $exceptions[] = $e->getMessage();
      }
      if(sizeof($exceptions) > 0){
        $toast = [
          'message' => join(', ', $exceptions),
          'type' => 'error'
        ];
      }
      return redirect()->route('gate.permission.index')->with('toast', $toast);
    }
    
    public function update($id, UpdateRequest $req){
      try{
        $permission = Permission::find($id);
        $permission->update($req->validated());
        $toast = [
          'message' => 'Permission <strong>'.$permission->name.'</strong> has <kbd>updated</kbd> successful!', 
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
        $permission = Permission::findOrFail($id);
        $permission->delete();
        $toast = [
          'message' => 'Permission <strong>'.$permission->name.'</strong> has <kbd>deleted</kbd> successfull!', 
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
    
    public function permissionForm($id){
      $role = Role::with('permissions')->find($id);
      $role_permissions = [];
      foreach ($role->permissions as $permission){
        $role_permissions[] = $permission->permission_id;
      }
      $perms = Permission::get();
      $permissions = [];
      foreach ($perms as $permission){
        $permissions[$permission->group_name][] = [
          'id' => $permission->id,
          'name' => $permission->name,
          'guard_name' => $permission->guard_name
        ];
      }
      //dd($grouped);
      return inertia('Gate/PermissionForm')->with([
        'permissions' => $permissions, 
        'role_id' => $id,
        'role_permissions' => $role_permissions,
        'role_name' => $role->name
      ]);
    }
    
    public function updateRolePermission($id, Request $req){
      $response = Role::find($id)->syncPermission($req->permission_id);
      if($response['type'] == 'success'){
        User::where('role_id', $id)->select('id')->get()->map(function($user){
          \DB::table('sessions')
              ->where('user_id', $user->id)
              ->delete();
        });
      }
      return redirect()->route('admin.gate.role.index')->with('toast', $response);
    }
}
