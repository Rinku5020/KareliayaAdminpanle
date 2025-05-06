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

    public function showGraphicsAndVideos(){
        return view ('graphics');
    }
}
