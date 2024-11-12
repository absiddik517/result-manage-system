<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function get(Request $req){
      return User::when(request()->inputs('name'), function($join, $name){
        $join->where('name', 'like', "%$name%");
      })
      ->select('name', 'id')
      ->limit(10)->get();
    }
}
