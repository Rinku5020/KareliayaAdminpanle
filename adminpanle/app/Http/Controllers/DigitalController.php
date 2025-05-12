<?php

namespace App\Http\Controllers;

use App\Models\digital;
use Illuminate\Http\Request;

class DigitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard');
    }


    public function showStore(){
        return view('store');
    }

    public function showDisplay(){
        return view('display');
    }
    public function showTemplate(){
        return view('template');
    }
   
    public function addNewStore(){
        return view('components.addstore');
    }
   
}
