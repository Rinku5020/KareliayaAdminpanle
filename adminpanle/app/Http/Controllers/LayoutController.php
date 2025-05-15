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
        $status = [true, false];


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
        'media' => 'required|json',
    ];

    $messages = [
        'layoutName.required' => 'Layout Name is required',
        'store_id.required' => 'Store ID is required',
        'displayMode.required' => 'Display Mode is required',
        'playlistName.required' => 'Playlist Name is required',
        'address.required' => 'Address is required',
        'logo.required' => 'Logo is required',
        'media.required' => 'Media selection is required',
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
        $uniqueId = 'L-' . rand(100000, 999999);
    } while (Layout::where('unique_id', $uniqueId)->exists());

      $layout->unique_id = $uniqueId;
    $layout->layoutName = $request->input('layoutName');
    $layout->store_id = $request->input('store_id');
    $layout->displayMode = $request->input('displayMode');
    $layout->playlistName = $request->input('playlistName');
    $layout->address = $request->input('address');


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
}




    // API Controller

public function getAllData($id)
{
    // Get the layout record (replace with your actual model)
    $layout = Layout::where('unique_id', $id)->first();

    if (!$layout) {
        return response()->json([
            'error' => true,
            'status' => false,
            'statusCode' => 404,
            'message' => 'Layout not found',
        ]);
    }

    // Decode zone data
    $zone1 = json_decode($layout->zone1, true);
    $zone2 = json_decode($layout->zone2, true);
    $zone3 = json_decode($layout->zone3, true);
    $zone4 = json_decode($layout->zone4, true);

    // Format zone1
    $formattedZone1 = [];
    foreach ($zone1 as $index => $item) {
        $formattedZone1[] = [
            'media' => [
                'filename' => $item['name']
            ],
            '_id' => "zone1-" . ($index + 1),
            'duration' => $item['duration']
        ];
    }

    // Format zone2
    $formattedZone2 = [];
    foreach ($zone2 as $index => $item) {
        $formattedZone2[] = [
            'media' => [
                'filename' => $item['name']
            ],
            '_id' => "zone2-" . ($index + 1),
            'duration' => $item['duration']
        ];
    }
    // Format zone3
    $formattedZone3 = [];
    foreach ($zone3 as $index => $item) {
        $formattedZone3[] = [
            'media' => [
                'filename' => $item['name']
            ],
            '_id' => "zone3-" . ($index + 1),
            'duration' => $item['duration']
        ];
    }
    // Format zone4
    $formattedZone4 = [];
    foreach ($zone4 as $index => $item) {
        $formattedZone4[] = [
            'media' => [
                'filename' => $item['name']
            ],
            '_id' => "zone4-" . ($index + 1),
            'duration' => $item['duration']
        ];
    }

    // Return formatted response
    return response()->json([
        'error' => false,
        'status' => true,
        'statusCode' => 200,
        'responseTimestamp' => now()->toIso8601String(),
        'data' => [
            '_id' => $layout->unique_id,
            'themeId' => $layout->id,
            'uniqueCode' => $layout->unique_id,
            'displaysize' => $layout->layoutName,
            'playlistName' => $layout->playlistName,
            'stores' => [$layout->store_id],
            'displayMode' => $layout->displayMode,
            'selectedDisplays' => [],
            'staticAddress' => $layout->address,
            'logo' => [
                'filename' => $layout->logo
            ],
            'scheduleType' => 'fixed',
            'recurring' => [
                'weekdays' => []
            ],
            'zone1' => $formattedZone1,
            'zone2' => $formattedZone2,
            'zone3' => $formattedZone3,
            'zone4' => $formattedZone4,
            'status' => $layout->status == "1",
            'displayStatus' => true,
            'updatedAt' => $layout->updated_at->toIso8601String()
        ]
    ]);
}

}
