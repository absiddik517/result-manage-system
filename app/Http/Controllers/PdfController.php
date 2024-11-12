<?php

namespace App\Http\Controllers;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function index(Request $req){
      dd($req->toArray());
      //config(['pdf.format'=> 'legal']);
      //$config = config('pdf.format');
      //dd($config);
      //return view('test');
      //jdj
      return $pdf = PDF::loadView('test')->stream('tt.pdf');
      dd('here');
      
    }
}
