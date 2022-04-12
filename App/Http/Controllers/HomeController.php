<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller{
    
    /* (static) view index padrão*/
    function index(){

        return view('site.index');

    }
}