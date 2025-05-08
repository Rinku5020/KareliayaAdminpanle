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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(digital $digital)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(digital $digital)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, digital $digital)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(digital $digital)
    {
        //
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
    public function showLayout(){
        return view('layout');
    }

    public function showGraphicsAndVideos(){
        return view ('graphics');
    }

    public function addStore(){
        return view('components.addstore');
    }
    public function createStore(Request $request){
        $request->validate([
            "storeId"=>"required",
            "name"=>"required",
            "phone"=>"required",
            "email"=>"required|email",
            "country"=>"required",
            "state"=>"required",
            "city"=>"required",
            "address"=>"required",
            "pincode"=>"required",
            "logo"=>"required|image|mimes:jpeg,png,jpg|max:2048",
        ],[
            "storeId.required"=>"Store ID is required",
            "name.required"=>"Store Name is required",
            "phone.required"=>"Phone number is required",
            "email.required"=>"Email is required",
            "country.required"=>"Country is required",
            "state.required"=>"State is required",
            "city.required"=>"City is required",
            "address.required"=>"Address is required",
            "pincode.required"=>"Zipcode is required",
            "logo.required"=>"Store Logo is required",
            "logo.image"=>"Store Logo must be an image",
            "logo.mimes"=>"Store Logo must be a jpeg, png, or jpg file",
            "logo.max"=>"Store Logo size must be less than 2MB"
        ]);
    }
}
