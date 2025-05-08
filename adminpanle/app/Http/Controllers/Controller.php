<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Controller
{
    public function showLogin(){
        return view('auth.login');
    }

}
