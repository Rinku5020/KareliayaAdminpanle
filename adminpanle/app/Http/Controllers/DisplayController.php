<?php

namespace App\Http\Controllers;

use App\Models\Display;
use App\Models\Store;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function showDisplay()
    {
        $display = Display::all();
        return view('display.display', ['display' => $display]);
    }

    public function addDisplay()
    {
        $stores = Store::all();
        do {
            $displayId = rand(100000, 999999);
        } while (Display::where('displayId', $displayId)->exists());
        return view('display.addDisplay', ['displayId' => $displayId, 'stores' => $stores]);
    }

    public function createDisplay(Request $request)
    {
        // Get stores for dropdown
        $stores = Store::all();

        $request->validate([
            'displayId' => 'required|unique:displays,displayId',
            'displayName' => 'required',
            'tags' => 'required',
            'store' => 'required|exists:stores,storeId',
            'timeZone' => 'required',
            'display' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
        ], [
            'displayId.required' => 'Display id is required',
            'displayId.unique' => 'This display ID already exists',
            'displayName.required' => 'Display name is required',
            'tags.required' => 'Tags are required',
            'store.required' => 'Store selection is required',
            'store.exists' => 'Selected store does not exist',
            'timeZone.required' => 'Time zone is required',
            'display.required' => 'Display type is required',
            'country.required' => 'Country is required',
            'state.required' => 'State is required',
            'city.required' => 'City is required',
            'address.required' => 'Address is required',
        ]);

        $display = new Display();
        $display->displayId = $request->displayId;
        $display->displayName = $request->displayName;
        $display->tags = $request->tags;
        $display->storeId = $request->store;
        $display->timeZone = $request->timeZone;
        $display->display = $request->display;
        $display->country = $request->country;
        $display->state = $request->state;
        $display->city = $request->city;
        $display->address = $request->address;
        $display->save();

        return redirect()->route('display')->with('success', 'Display created successfully');
    }
}
