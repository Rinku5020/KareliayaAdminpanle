<?php
namespace App\Http\Controllers;
use App\Models\Display;
use App\Models\Graphic;
use App\Models\layout;
use App\Models\Media;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
class LayoutController extends Controller
{
    use ValidatesRequests;
    public function showLayout()
    {
        $layouts = layout::all();
        $status = [true, false];
        return view('layout.layout', compact('layouts'));
    }
    public function AddLayout(Request $request)
    {
        $graphics = Graphic::all();
        $stores = Display::all()->unique('store_id');
        $displays = Display::all();
        return view('layout.addlayout', compact('graphics', 'stores', 'displays'));
    }
    public function layoutStore(Request $request)
    {
        try {
            $rules = [
                'layoutName' => 'required',
                'store_id' => 'required',
                'displayMode' => 'required',
                'playlistName' => 'required',
                'address' => 'required',
                'logo' => 'required',
                'media' => 'required|json',
                'selectedDisplays' => 'required|json',
            ];
            $messages = [
                'layoutName.required' => 'Layout Name is required',
                'store_id.required' => 'Store ID is required',
                'displayMode.required' => 'Display Mode is required',
                'playlistName.required' => 'Playlist Name is required',
                'address.required' => 'Address is required',
                'logo.required' => 'Logo is required',
                'media.required' => 'Media selection is required',
                'selectedDisplays.required' => 'Selected Displays are required',
            ];
            $this->validate($request, $rules, $messages);
            $layout = new Layout();
            // Handle logo upload
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/layout'), $fileName);
                $layout->logo = $fileName;
            }
            // Generate unique layout ID
            do {
                $uniqueId =  rand(100000, 999999);
            } while (Layout::where('unique_id', $uniqueId)->exists());
            $layout->unique_id = $uniqueId;
            $layout->account_id = session('account_id');
            $layout->layoutName = $request->input('layoutName');
            $layout->store_id = $request->input('store_id');
            $layout->displayMode = $request->input('displayMode');
            $layout->playlistName = $request->input('playlistName');
            $layout->address = $request->input('address');

            // Handle selected displays
            $layout->selectedDisplays = $request->input('selectedDisplays'); // saves as real JSON array
           
            // Handle media JSON input
            // Get zone-wise media data from the form submission
            $flatMediaList = json_decode($request->input('media'), true);
            // Group media by zone
            $zonesData = [
                'zone1' => [],
                'zone2' => [],
                'zone3' => [],
                'zone4' => [],
            ];
            foreach ($flatMediaList as $media) {
                if (isset($media['zone']) && isset($zonesData[$media['zone']])) {
                    $zonesData[$media['zone']][] = $media;
                }
            }
            // Save media data to respective zones
            $layout->zone1 = json_encode($zonesData['zone1'] ?? []);
            $layout->zone2 = json_encode($zonesData['zone2'] ?? []);
            $layout->zone3 = json_encode($zonesData['zone3'] ?? []);
            $layout->zone4 = json_encode($zonesData['zone4'] ?? []);
            $layout->save();
            return redirect()->route('layout')->with('success', 'Layout added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('layout')->with('error', 'Layout added failed.');
        }
    }
 public function status($id)
    {
        $layout = layout::findOrFail($id);
      
        $layout->status = $layout->status == 1 ? 0 : 1;
        $layout->save();
        return redirect()->route('layout')->with('success', 'Layout status updated successfully!');
    }


    public function saveMediaToDatabase(array $mediaList)
{
    foreach ($mediaList as $mediaItem) {
        // Example: check required fields exist before inserting
        if (
            isset($mediaItem['originalname']) &&
            isset($mediaItem['encoding']) &&
            isset($mediaItem['mimetype']) &&
            isset($mediaItem['size']) &&
            isset($mediaItem['path'])
        ) {
            Media::create([
                'account_id'   => session('account_id'), 
                'originalname' => $mediaItem['originalname'],
                'encoding'     => $mediaItem['encoding'],
                'mimetype'     => $mediaItem['mimetype'],
                'size'         => $mediaItem['size'],
                'path'         => $mediaItem['path'],
            ]);
        }
    }
}

































































    
    
    public function editLayout($id)
    {
        $layout = layout::findOrFail($id);
        $graphics = Graphic::all();
        $stores = Display::all()->unique('store_id');
        $displays = Display::all();
        return view('layout.editlayout', compact('layout', 'graphics', 'stores', 'displays'));
    }
    public function updateLayout(Request $request, $id)
    {
        $layout = layout::findOrFail($id);
        $rules = [
            'layoutName' => 'required',
            'store_id' => 'required',
            'displayMode' => 'required',
            'playlistName' => 'required',
            'address' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'media' => 'required|json',
            'selectedDisplays' => 'required|json',
        ];
        $messages = [
            'layoutName.required' => 'Layout Name is required',
            'store_id.required' => 'Store ID is required',
            'displayMode.required' => 'Display Mode is required',
            'playlistName.required' => 'Playlist Name is required',
            'address.required' => 'Address is required',
            'logo.image' => 'Logo must be an image file.',
            'logo.mimes' => 'Logo must be a file of type: jpeg, png, jpg, gif.',
            'logo.max' => 'Logo may not be greater than 2MB.',
            'media.required' => 'Media selection is required',
            'selectedDisplays.required' => 'Selected Displays are required',
        ];
        $this->validate($request, $rules, $messages);
        if ($request->hasFile('logo')) {
            // Handle logo upload
            $file = $request->file('logo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/layout'), $fileName);
            $layout->logo = $fileName;
        }
        // Update layout details
        $layout->layoutName = $request->input('layoutName');
        $layout->store_id = $request->input('store_id');
        $layout->displayMode = $request->input('displayMode');
        $layout->playlistName = $request->input('playlistName');
        $layout->address = $request->input('address');
        // Handle selected displays
        $displayIds = json_decode($request->input('selectedDisplays'), true);
        // Handle media JSON input
        // Get zone-wise media data from the form submission
        $flatMediaList = json_decode($request->input('media'), true);
        //
        // Group media by zone
        $zonesData = [
            'zone1' => [],
            'zone2' => [],
            'zone3' => [],
            'zone4' => [],
        ];      

        foreach ($flatMediaList as $media) {
            if (isset($media['zone']) && isset($zonesData[$media['zone']])) {
                $zonesData[$media['zone']][] = $media;
            }
        }
        // Save media data to respective zones
        $layout->zone1 = json_encode($zonesData['zone1'] ?? []);
        $layout->zone2 = json_encode($zonesData['zone2'] ?? []);
        $layout->zone3 = json_encode($zonesData['zone3'] ?? []);
        $layout->zone4 = json_encode($zonesData['zone4'] ?? []);
        $layout->selectedDisplays = $request->input('selectedDisplays');
        $layout->save();
        return redirect()->route('layout')->with('success', 'Layout updated successfully.');
    }
    
}
