<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(){
      //dd(testing('lorem', 'ipsom', 'dorem', 'site', 'emat'));
      return redirect()->route('admin.dashboard.index');
    }
}
