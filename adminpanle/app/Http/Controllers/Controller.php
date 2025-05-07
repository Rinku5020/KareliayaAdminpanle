<?php

namespace App\Http\Controllers;

class Controller
{
    public function showLogin(){
        return view('auth.login');
    }

    public function login(){
        return "hello";
    }
}
