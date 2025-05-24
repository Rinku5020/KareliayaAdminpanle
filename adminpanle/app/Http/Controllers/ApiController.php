<?php

namespace App\Http\Controllers;

use App\Models\layout;
use App\Models\Logs;
use App\Models\Verifycode;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    use ValidatesRequests;
    public function getAllData($id)
    {
        $layout = layout::where('id', $id)->first();
        if (!$layout) {
            return response()->json([
                'error' => true,
                'status' => false,
                'statusCode' => 404,
                'message' => 'Layout not found',
            ]);
        }
        // Decode all zones
        $zones = [
            'zone1' => json_decode($layout->zone1, true),
            'zone2' => json_decode($layout->zone2, true),
            'zone3' => json_decode($layout->zone3, true),
            'zone4' => json_decode($layout->zone4, true),
        ];
        // Get account ID for media items
        $accountId = $layout->account_id;
        // Prepare all zones
        $formattedZones = [];
        foreach ($zones as $zoneKey => $zoneItems) {
            $formattedZones[$zoneKey] = [];
            foreach ($zoneItems as $item) {
                $filename = $item['name'];
                $path = 'uploads/media/' . $filename;
                $fullPath = public_path($path);
                $mediaId = $layout->id;
                // Format differently for zone1 (media = string), others = object
                $media = [
                    'account' => $accountId,
                    '_id' => $mediaId,
                    'originalname' => $filename,
                    'encoding' => '7bit',
                    'mimetype' => File::exists($fullPath) ? File::mimeType($fullPath) : 'application/octet-stream',
                    'filename' => $filename,
                    'path' => str_replace('/', '/', ($path)),
                    'size' => File::exists($fullPath) ? File::size($fullPath) : 0,
                    'createdAt' => $item['createdAt'] ?? now()->toISOString(),
                    'updatedAt' => $item['updatedAt'] ?? now()->toISOString(),
                ];
                $formattedZones[$zoneKey][] = [
                    'media' => $media,
                    '_id' => $mediaId,
                    'duration' => $item['duration']
                ];
            }
        }
        // Format logo
        $logoPath = 'uploads/layout/' . $layout->logo;
        $logo = [
            'account' => $accountId,
            '_id' => $layout->id,
            'originalname' => $layout->logo,
            'encoding' => '7bit',
            'mimetype' => File::mimeType(public_path($logoPath)),
            'filename' => $layout->logo,
            'path' => str_replace('/', '/', ($logoPath)),
            'size' => File::size(public_path($logoPath)),
            'createdAt' => now()->toISOString(),
            'updatedAt' => now()->toISOString(),
        ];
        // Final response
        return response()->json([
            'error' => false,
            'status' => true,
            'statusCode' => 200,
            'responseTimestamp' => now()->toIso8601String(),
            'data' => [
                '_id' => $layout->id,
                'uniqueCode' => $layout->unique_id,
                'playlistName' => $layout->playlistName,
                'stores' => [$layout->store_id],
                'displayMode' => $layout->displayMode,
                'displaysize' => $layout->layoutName,
                'displayStatus' => (bool) $layout->status,
                'selectedDisplays' => $layout->selectedDisplays,
                'logo' => $logo,
                'scheduleType' => 'fixed',
                'recurring' => [
                    'weekdays' => [0, 1, 2, 3, 4, 5, 6]
                ],
                'zone1' => $formattedZones['zone1'],
                'zone2' => $formattedZones['zone2'],
                'zone3' => $formattedZones['zone3'],
                'zone4' => $formattedZones['zone4'],
            ]
        ]);
    }
    public function verifyCode(Request $request)
    {
        // ✅ Extract device data
        $deviceToken = $request->input('deviceToken');
        $ipAddress = $request->input('ipAddress');
        $ipv4Address = $request->input('ipv4Address');
        $deviceBrand = $request->input('deviceBrand');
        $display = $request->input('display');
        $code = $request->input('code');
        // ✅ Insert/update device record
        $device = Verifycode::updateOrCreate(
            ['device_token' => $deviceToken],
            [
                'ip_address' => $ipAddress,
                'ipv4_address' => $ipv4Address,
                'device_brand' => $deviceBrand,
                'display' => $display,
                'unique_code' => $code,
            ]
        );
        // ✅ Find layout
        $layout = layout::where('unique_id', $code)->first();
        // ✅ If layout not found
        if (!$layout) {
            return response()->json([
                'error' => true,
                'status' => false,
                'statusCode' => 404,
                'message' => 'Layout not found',
                'data' => (object)[]
            ]);
        }
        // ✅ Return the formatted playlist response directly
        return $this->getAllData($layout->id);
    }
    public function devices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deviceToken' => 'required|string',
            'ipAddress' => 'required|string',
            'ipv4Address' => 'required|string',
            'deviceBrand' => 'required|string',
            'display' => 'required|array',
            'display.width' => 'required|numeric',
            'display.height' => 'required|numeric',
            'logFileContents' => 'sometimes|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'statusCode' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $data = $validator->validated();
        $device = Verifycode::where('device_token', $data['deviceToken'])->first();
        if (!$device) {
            return response()->json([
                'status' => false,
                'playListStatus' => false,
                'message' => 'Device not found',
                'data' => [],
                'playlist' => (object)[],
            ]);
        }
        if (!empty($data['logFileContents'])) {
            $logContent = $data['logFileContents'];
            $logLines = explode("\n", $logContent);
            $timestamp = now()->toDateTimeString();
            $combinedMessage = '';
            foreach ($logLines as $line) {
                if (trim($line)) {
                    $combinedMessage .= "$line\n";
                }
            }
            $combinedMessage = trim($combinedMessage);
            // Update or create DB log entry
            $today = now()->toDateString();
            $log = Logs::updateOrCreate(
                [
                    'device_token' => $data['deviceToken'],
                    'log_date' => $today
                ],
                [
                    'action' => 'isOnlineCheck',
                    'message' => $combinedMessage,
                    'updated_at' => now(),
                    'log_date' => $today
                ]
            );
            // File log creation
            $date = now()->format('Y-m-d');
            $deviceToken = $data['deviceToken'];
            $logFolder = public_path('logs');
            $logFilePath = "$logFolder/media_log_{$date}_{$deviceToken}.txt";
            if (!File::exists($logFolder)) {
                File::makeDirectory($logFolder, 0755, true);
            }
            $formattedLogs = "[$timestamp] Device: {$data['deviceToken']} | $combinedMessage" . PHP_EOL;
            File::append($logFilePath, $formattedLogs);
        }
        // ✅ Layout / Playlist logic
        $layout = layout::where('unique_id', $device->unique_code)->first();
        $playlistResponse = $layout ? $this->getAllData($layout->id) : null;
        $playlistData = $playlistResponse ? $playlistResponse->getData(true) : [];
        return response()->json([
            'status' => true,
            'playListStatus' => (bool)$layout,
            'data' => [
                'display' => $data['display'],
                '_id' => $device->_id ?? $device->id,
                'uniqueCode' => $device->unique_code,
                'ipAddress' => $device->ip_address,
                'deviceToken' => $device->device_token,
                'deviceBrand' => $device->device_brand,
                'ipv4Address' => $device->ipv4_address,
                'createdAt' => $device->created_at->toISOString(),
                'updatedAt' => $device->updated_at->toISOString(),
                '__v' => 0
            ],
            'playlist' => $playlistData['data'] ?? (object)[]
        ]);
    }
    // log api
    public function getLogs(Request $request)
    {
        $logs = Logs::orderBy('created_at', 'desc')->paginate(50);
        $logFilePath = public_path('logs/media_log.txt');
        $logFileUrl = null;
        if (File::exists($logFilePath)) {
            $logFileUrl = url('logs/media_log.txt');
        }
        return response()->json([
            'error' => false,
            'status' => true,
            'log_file' => $logFileUrl,
            'data' => $logs
        ]);
    }
}
