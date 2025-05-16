<?php

namespace App\Http\Controllers;

use App\Models\digital;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DigitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Auth::check()){
            return redirect()->route('showLogin');
        }
       
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

    public function showStore()
    {
        if(!Auth::check()){
            return redirect()->route('showLogin');
        }
        $authId = Auth::user()->id;
        dd($authId);
        $store = Store::all();
        return view('store.store', ['store' => $store]);
    }

    public function showTemplate()
    {
        return view('template');
    }
    public function showLayout()
    {
        return view('layout');
    }

    public function addNewStore()
    {
        do {
            $storeId = 'S-' . rand(100000, 999999);
        } while (Store::where('storeId', $storeId)->exists());
        return view('store.addstore', compact('storeId'));
    }

    public function createStore(Request $request)
    {
        // Validate inputs (including the generated storeId)
        $request->validate([
            'storeId' => 'required|unique:stores,storeId',
            'name'    => 'required',
            'phone'   => 'required',
            'email'   => 'required|email|unique:stores,email',
            'country' => 'required',
            'state'   => 'required',
            'city'    => 'required',
            'address' => 'required',
            'pincode' => 'required',
            'logo'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'storeId.required' => 'Store ID is required',
            'storeId.unique'   => 'Store ID must be unique',
            'name.required'    => 'Store Name is required',
            'phone.required'   => 'Phone number is required',
            'email.required'   => 'Email is required',
            'email.unique'     => 'Email already exists',
            'country.required' => 'Country is required',
            'state.required'   => 'State is required',
            'city.required'    => 'City is required',
            'address.required' => 'Address is required',
            'pincode.required' => 'Zipcode is required',
            'logo.required'    => 'Store Logo is required',
            'logo.image'       => 'Store Logo must be an image',
            'logo.mimes'       => 'Store Logo must be a jpeg, png, or jpg file',
            'logo.max'         => 'Store Logo size must be less than 2MB',
        ]);

        // Handle logo upload
        $logoName = null;
        if ($request->hasFile('logo')) {
            $logo     = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/store'), $logoName);
        }

        // Persist the new Store
        $store = new Store();
        $store->storeId = $request->storeId;
        $store->name    = $request->name;
        $store->phone   = $request->phone;
        $store->email   = $request->email;
        $store->country = $request->country;
        $store->state   = $request->state;
        $store->city    = $request->city;
        $store->address = $request->address;
        $store->pincode = $request->pincode;
        $store->logo    = $logoName;
        $store->save();

        return redirect()->route('store')
            ->with('success', 'Store created successfully!');
    }


    public function editStore($storeId)
    {
        $store = Store::where('storeId', $storeId)->firstOrFail();
        return view('store.editStore', compact('store'));
    }


    public function updateStore(Request $request, $storeId)
    {
        $store = Store::findOrFail($storeId);

        $request->validate([
            "storeId" => "required",
            "name" => "required",
            "phone" => "required",
            "email" => "required|email|unique:stores,email,{$store->storeId},storeId",
            "country" => "required",
            "state" => "required",
            "city" => "required",
            "address" => "required",
            "pincode" => "required",
            "logo" => "nullable|image|mimes:jpeg,png,jpg|max:2048"
        ], [
            "name.required" => "Store Name is required",
            "phone.required" => "Phone number is required",
            "email.required" => "Email is required",
            "email.unique" => "Email already exists",
            "country.required" => "Country is required",
            "state.required" => "State is required",
            "city.required" => "City is required",
            "address.required" => "Address is required",
            "pincode.required" => "Zipcode is required",
            "logo.image" => "Store Logo must be an image",
            "logo.mimes" => "Store Logo must be a jpeg, png, or jpg file",
            "logo.max" => "Store Logo size must be less than 2MB"
        ]);

        if ($request->hasFile('logo')) {
            if ($store->logo && file_exists(public_path('uploads/store/' . $store->logo))) {
                unlink(public_path('uploads/store/' . $store->logo));
            }

            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/store'), $logoName);
            $store->logo = $logoName;
        }

        $store->storeId = $request->storeId;
        $store->name = $request->name;
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->country = $request->country;
        $store->state = $request->state;
        $store->city = $request->city;
        $store->address = $request->address;
        $store->pincode = $request->pincode;

        $store->save();

        return redirect('store')->with('success', 'Store updated successfully!');
    }


    public function deleteStore($id)
    {
        $store = Store::findOrFail($id);

        if ($store->logo && file_exists(public_path('uploads/store/' . $store->logo))) {
            unlink(public_path('uploads/store/' . $store->logo));
        }

        $store->delete();

        return redirect()->route('store')->with('success', 'Store deleted successfully!');
    }



    public function status($id)
    {
        $store = Store::findOrFail($id);

        // Toggle the status
        $store->status = $store->status == 1 ? 0 : 1;

        $store->save();

        return redirect()->route('store')->with('success', 'Store status updated successfully!');
   
    }   
   
    
    }

 