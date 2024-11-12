<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $guarded = [];
    protected $perPage = 5;
    
    
    public function permissions(){
      return $this->hasMany(RoleHasPermission::class);
    }
    
    public function syncPermission($permissions){
      $this->permissions()->delete();
      $permissionClass = [];
      foreach ($permissions as $permission){
        $permissionClass[] = new RoleHasPermission([
          'permission_id' => $permission
        ]);
      }
      try{
        $this->permissions()->saveMany($permissionClass);
        return [
          'type' => 'success',
          'message' => "Permissions of $this->name has been updated."
        ];
      }catch(\Exception $e){
        return [
          'message' => exception_message($e),
          'type' => 'error'
        ];
      }
    }
}
