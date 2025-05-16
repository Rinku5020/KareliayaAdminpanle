<?php

namespace App\Http\Controllers;

use App\Models\layout;
use Illuminate\Http\Request;

class ApiController extends Controller
{
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
    
}
