<?php

namespace App\Http\Controllers;

use App\Models\Display;
use App\Models\Graphic;
use App\Models\layout;
use App\Models\Logs;
use App\Models\Media;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class LayoutController extends Controller
{
    use ValidatesRequests;
    
    public function showLayout()
    {
        $role = session('role');
        $userId = session('account_id');
        if ($role === 'admin') {
            $layouts = layout::all();
            $displays = Display::all();
        } else {
            $layouts = layout::where('account_id', $userId)->get();
            $displays = Display::where('account_id', $userId)->get();
        }
        $status = [true, false];


        return view('layout.layout', compact('layouts', 'displays'));
    }
    
    public function AddLayout(Request $request)
    {
        $role = session('role');
        $userId = session('account_id');
        if ($role === 'admin') {
            $graphics = Graphic::all();
            $displays = Display::all();
        } else {
            $graphics = Graphic::where('account_id', $userId)->get();
            $displays = Display::where('account_id', $userId)->get();
        }
        return view('layout.addlayout', compact('graphics', 'displays'));
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
                'selectedDisplays' => 'required',
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
            $layout->selectedDisplays = $request->input('selectedDisplays');


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
            //   return ($e->getMessage());
        }
    }
    public function status($id)
    {
        $layout = layout::findOrFail($id);
        $layout->status = $layout->status == 1 ? 0 : 1;
        $layout->save();
        return redirect()->route('layout')->with('success', 'Layout status updated successfully!');
    }
    public function mediaLogs(request $request)
    {

        $logs = Logs::orderBy('created_at', 'desc')->paginate(50);

        $logFilePath = public_path('logs/media_log.txt');
        $logFileUrl = null;


        if (File::exists($logFilePath)) {
            $logFileUrl = url('logs/media_log.txt');
        }



        return view('layout.medialogs', compact('logs'));
    }


    public function downloadDeviceLog($date, $deviceToken)
    {
        $sanitizedToken =  $deviceToken;
        $fileName = "media_log_{$date}_{$sanitizedToken}.txt";
        $file = public_path("logs/$fileName");

        if (file_exists($file)) {
            return response()->download($file, $fileName, [
                'Content-Type' => 'text/plain',
            ]);
        }

        return redirect()->back()->with('error', "Log file for device {$deviceToken} on {$date} not found.");
    }
}
