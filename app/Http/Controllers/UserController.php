<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auth\Role;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function index(){
    $users = UserResource::collection(User::orderBy('users.id', 'asc')
        ->leftJoin('roles', 'users.role_id', 'roles.id')
        ->when(request()->input('search'), function($query, $text){
          $query->where('users.name', 'like', "%$text%")
                ->orWhere('users.email', 'like', "%$text%")
                ->orWhere('roles.name', 'like', "%$text%");
        })
        ->select(['users.*', 'roles.name as role_name'])
        ->paginate(request()->input('per_page') ?? 5)->withQueryString());
    $params = [
      'search' => request()->input('search'),
      'per_page' => request()->input('per_page') ?? 5,
    ];
    return inertia('User/Index')->with([
      'users' => $users,
      'params' => $params
    ]);
  }
  public function create(){
    $roles = pluckByKey(Role::select('name', 'id')->get());
    return inertia('User/Create')->with([
      'roles' => $roles
    ]);
  }
  
  public function store(StoreRequest $request){
    try {
      User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $request->role_id,
      ]);
      return redirect()->route('user.index')->with('toast', [
        'message' => 'User created successfull.',
        'type' => 'success'
      ]);
    }catch(\Exception $e){
      return redirect()->back()->with('toast', [
        'message' => exception_message($e),
        'type' => 'success'
      ]);
    }
  }
  
  public function edit($user){
    $logged_in = auth()->user()->permissions();
    //dd($logged_in);
    $roles = pluckByKey(Role::select('name', 'id')->get());
    $user = User::where('id', $user)->select('id', 'name', 'email', 'role_id')->first();
    return inertia('User/Edit')->with([
      'user' => $user,
      'roles' => $roles,
      'logged' => $logged_in,
    ]);
  }
  
  public function update($id, Request $request){
    User::find($id)->update([
      'name' => $request->name,
      'email' => $request->email,
      'role_id' => $request->role_id
    ]);
    \DB::table('sessions')->where('user_id', $id)->delete();
    return redirect()->route('user.index')->with('toast', [
      'type' => 'success',
      'message' => "User has been updated"
    ]);
  }
  public function delete(User $user){
    
  }
}
