<?php

namespace App\Http\Controllers;

use App\Models\Display;
use App\Models\Store;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function showDisplay()
    {
        $display = Display::with('store')->get();
        $stores = Store::all();
        return view('display.display', ['display' => $display, 'stores' => $stores]);
    }

    public function addDisplay()
    {
        $stores = Store::all();
        do {
            $display_id = rand(100000, 999999);
        } while (Display::where('display_id', $display_id)->exists());
        return view('display.addDisplay', ['display_id' => $display_id, 'stores' => $stores]);
    }

    public function createDisplay(Request $request)
    {
        try {
            $request->validate([
                'display_id' => 'required|unique:displays,display_id',
                'name' => 'required',
                'tags' => 'required',
                'store' => 'required|exists:stores,storeId',
                'time_zone' => 'required',
                'display_mode' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
            ], [
                'display_id.required' => 'Display id is required',
                'display_id.unique' => 'This display ID already exists',
                'name.required' => 'Display name is required',
                'tags.required' => 'Tags are required',
                'store.required' => 'Store selection is required',
                'store.exists' => 'Selected store does not exist',
                'time_zone.required' => 'Time zone is required',
                'display_mode.required' => 'Display type is required',
                'country.required' => 'Country is required',
                'state.required' => 'State is required',
                'city.required' => 'City is required',
                'address.required' => 'Address is required',
            ]);

            $display = new Display();
            $display->display_id = $request->display_id;
            $display->name = $request->name;
            $display->tags = $request->tags;
            $display->store_id = $request->store;
            $display->time_zone = $request->time_zone;
            $display->display_mode = $request->display_mode;
            $display->country = $request->country;
            $display->state = $request->state;
            $display->city = $request->city;
            $display->address = $request->address;
            $display->save();


            return redirect()->route('display')->with('success', 'Display created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function editDisplay($display_id){
        $display = Display::where('display_id', $display_id)->firstOrFail();
        $stores = Store::all();
        return view('display.editDisplay', compact('display', 'stores'));
    }
}
