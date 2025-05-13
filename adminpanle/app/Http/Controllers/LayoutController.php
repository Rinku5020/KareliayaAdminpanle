<?php
namespace App\Http\Controllers;
use App\Models\Graphic;
use App\Models\layout;
use App\Models\Media;
use App\Models\Store;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;


class LayoutController extends Controller
{
    use ValidatesRequests;
    public function showLayout()
    {
        $layouts = Layout::all();
        return view('layout.layout', compact('layouts'));
    }
    public function AddLayout(Request $request)
    {
        $store_id = Store::all();
        $graphics = Graphic::all();
        return view('layout.addlayout', compact('store_id', 'graphics'));
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
        // Check if the logo file is present in the request
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/layout'), $fileName);
            $layout->logo  = $fileName;
        }
        // Generate a unique ID for the layout
        do {
            $uniqueId = 'L-' . rand(100000, 999999);
        } while (Layout::where('unique_id', $uniqueId)->exists());
        $layout->unique_id = $uniqueId;
        $layout->layoutName = $request->input('layoutName');
        $layout->store_id = $request->input('store_id');
        $layout->displayMode = $request->input('displayMode');
        $layout->playlistName = $request->input('playlistName');
        $layout->address = $request->input('address');
        $layout->select_zone = $request->input('select_zone');
        $layout->save();
        // Save the media items
         $mediaItems = json_decode($request->input('media'), true);

            if ($mediaItems) {
                foreach ($mediaItems as $item) {
                    Media::create([
                        'layout_unique_id' => $layout->unique_id,
                        'media_name' => $item['name'],
                        'media_type' => $item['type'],
                        'duration' => $item['duration']
                    ]);
                }
            }

        return redirect()->route('layout')->with('success', 'Layout added successfully.');
    }



// API Controller

  public function getAllData($id)
{
    $layout = Layout::where('unique_id', $id)->first();

  


    if (!$layout) {
        return response()->json(['error' => 'Layout not found'], 404);
    }
       $media = Media::where('layout_unique_id', $layout->unique_id)->get();
   
    return response()->json([
        'layout' => $layout,
        'media' => $media
    ]);
}

}
  


