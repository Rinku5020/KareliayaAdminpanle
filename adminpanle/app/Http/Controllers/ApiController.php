<?php

namespace App\Http\Controllers;

use App\Models\layout;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    use ValidatesRequests;
    public function getAllData($id)
    {
        // Get the layout record (replace with your actual model)
        $layout = layout::where('unique_id', $id)->first();

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
                'selectedDisplays' => json_decode($layout->selectedDisplays, true),

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









// second API  VerifyCode




    public function verifyCode(Request $request)
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

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'status' => false,
                'statusCode' => 422,
                'responseTimestamp' => now(),
                'message' => $validator->errors()
            ], 422);
        }

        $layout = new Layout();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/' . date('Y') . '/' . date('m') . '/';
            $file->move(public_path($filePath), $fileName);
            $layout->logo = $filePath . $fileName;
        }

        // Generate unique layout ID
        do {
            $uniqueId = rand(100000, 999999);
        } while (Layout::where('unique_id', $uniqueId)->exists());

        $layout->unique_id = $uniqueId;
        $layout->layoutName = $request->input('layoutName');
        $layout->store_id = $request->input('store_id');
        $layout->displayMode = $request->input('displayMode');
        $layout->playlistName = $request->input('playlistName');
        $layout->address = $request->input('address');
        $layout->selectedDisplays = $request->input('selectedDisplays');

        // Handle media zones
        $flatMediaList = json_decode($request->input('media'), true);
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

        $layout->zone1 = json_encode($zonesData['zone1']);
        $layout->zone2 = json_encode($zonesData['zone2']);
        $layout->zone3 = json_encode($zonesData['zone3']);
        $layout->zone4 = json_encode($zonesData['zone4']);

        $layout->save();

        return response()->json([
            "error" => false,
            "status" => true,
            "statusCode" => 200,
            "responseTimestamp" => Carbon::now()->toISOString(),
            "data" => [
                "_id" => $layout->id,
                "uniqueCode" => $layout->unique_id,
                "playlistName" => $layout->playlistName,
                "stores" => [$layout->store_id],
                "displayMode" => $layout->displayMode,
                "displaysize" =>  $layout->layoutName, 
                "selectedDisplays" => json_decode($layout->selectedDisplays),
                "logo" => [
                    "_id" => uniqid(),
                    "account" => "621f5d5fdf6f052280354bb2", // Or dynamically fetch
                    "originalname" => $file->getClientOriginalName() ?? '',
                    "encoding" => "7bit",
                    "mimetype" => $file->getClientMimeType() ?? '',
                    "filename" => $fileName,
                    "path" => $filePath . $fileName,
                    "size" => $file->getSize(),
                    "createdAt" => now()->toISOString(),
                    "updatedAt" => now()->toISOString(),
                ],
                "scheduleType" => "fixed", // static or configurable
                "recurring" => [
                    "weekdays" => [0,1,2,3,4,5,6]
                ],
                "zone1" => $zonesData['zone1'],
                "zone2" => $zonesData['zone2'],
                "zone3" => $zonesData['zone3'],
                "zone4" => $zonesData['zone4'],
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'status' => false,
            'statusCode' => 500,
            'responseTimestamp' => now(),
            'message' => $e->getMessage()
        ], 500);
    }
    }

public function devices(Request $request)
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

            $logoData = [
                "account" => "", 
                "_id" => uniqid(),
                "originalname" => $file->getClientOriginalName(),
                "encoding" => "",
                "mimetype" => $file->getMimeType(),
                "filename" => $fileName,
                "path" => 'uploads/layout/' . $fileName,
                "size" => $file->getSize(),
                "createdAt" => now()->toIso8601String(),
                "updatedAt" => now()->toIso8601String(),
            ];
        }

        // Generate unique layout ID
        do {
            $uniqueId = rand(100000, 999999);
        } while (Layout::where('unique_id', $uniqueId)->exists());

        $layout->unique_id = $uniqueId;
        $layout->layoutName = $request->input('layoutName');
        $layout->store_id = $request->input('store_id');
        $layout->displayMode = $request->input('displayMode');
        $layout->playlistName = $request->input('playlistName');
        $layout->address = $request->input('address');
        $layout->selectedDisplays = $request->input('selectedDisplays');

        // Handle media and zone data
        $flatMediaList = json_decode($request->input('media'), true);

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

        $layout->zone1 = json_encode($zonesData['zone1']);
        $layout->zone2 = json_encode($zonesData['zone2']);
        $layout->zone3 = json_encode($zonesData['zone3']);
        $layout->zone4 = json_encode($zonesData['zone4']);

        $layout->save();

        // Construct response
        return response()->json([
            "error" => false,
            "status" => true,
            "statusCode" => 200,
            "responseTimestamp" => now()->toIso8601String(),
            "data" => [
                "_id" => $layout->_id ?? $layout->id,
                "uniqueCode" => $layout->unique_id,
                "playlistName" => $layout->playlistName,
                "stores" => [$layout->store_id],
                "displayMode" => $layout->displayMode,
                "displaysize" => "layout1",
                "selectedDisplays" => json_decode($layout->selectedDisplays),
                "logo" => $logoData ?? null,
                "scheduleType" => "fixed", // Static or dynamic based on business logic
                "recurring" => [
                    "weekdays" => [0, 1, 2, 3, 4, 5, 6]
                ],
                "zone1" => $zonesData['zone1'],
                "zone2" => $zonesData['zone2'],
                "zone3" => $zonesData['zone3'],
                "zone4" => $zonesData['zone4'],
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            "error" => true,
            "status" => false,
            "statusCode" => 500,
            "message" => $e->getMessage(),
            "responseTimestamp" => now()->toIso8601String()
        ], 500);
    }
}


}