<?php

namespace App\Http\Controllers;

use App\Models\layout;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    use ValidatesRequests;

    public function showLayout()
    {
        return view('layout.layout');
    }
    public function AddLayout()
    {
        return view('layout.addlayout');
    }


    public function layoutStore(Request $request)
    {

        $rules = [
            'layoutName' => 'required',
            'store_id' => 'required',
            'displayMode' => 'required',
            'playlistName' => 'required',
            'address' => 'required',
            'logo' => 'required',
            'select_zone' => 'required',

        ];
        $massages = [
            'layoutName.required' => 'Layout Name is required',
            'store_id.required' => 'Store ID is required',
            'displayMode.required' => 'Display Mode is required',
            'playlistName.required' => 'Playlist Name is required',
            'address.required' => 'Address is required',
            'logo.required' => 'Logo is required',
            'select_zone.required' => 'Select Zone is required',
        ];
        $this->validate($request, $rules, $massages);
        $layout = new layout();
        $layout->layoutName = $request->input('layoutName');
        $layout->store_id = $request->input('store_id');
        $layout->displayMode = $request->input('displayMode');
        $layout->playlistName = $request->input('playlistName');
        $layout->address = $request->input('address');
        $layout->logo = $request->input('logo');
        $layout->select_zone = $request->input('Select_zone');
        $layout->save();


        return redirect()->route('layout.showLayout')->with('success', 'Layout added successfully.');
    }
}
