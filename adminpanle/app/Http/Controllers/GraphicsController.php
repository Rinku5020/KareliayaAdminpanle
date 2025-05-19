<?php

namespace App\Http\Controllers;

use App\Models\Graphic;
use Illuminate\Http\Request;

use Illuminate\Foundation\Validation\ValidatesRequests;

class GraphicsController extends Controller
{
    use ValidatesRequests;
    public function validateAndUploadMedia(Request $request)
    {
        

        $rules = [
            'name' => 'required|alpha',
            'media_id' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048',
            'type' => 'required|in:video,image',
            'type' => 'required',
        ];
        $messages = [
        'name.required' => 'Name is required',
        'name.alpha' => 'Name must be alphabetic',
        'media_id.required' => 'Media file is required',
        'type.required' => 'Type is required',
        ];
        $this->validate($request, $rules, $messages);

        $graphics = new Graphic();
        if ($request->hasFile('media_id')) {
            $file = $request->file('media_id');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/media'), $fileName);
            $graphics->media_id = $fileName;
        }

        $graphics->name = $request->input('name');
        $graphics->type = $request->input('type');
        $graphics->account_id = session('account_id');
        $graphics->save();
        return redirect()->route('graphics')->with('success', 'Media uploaded successfully');
    }




    public function showGraphicsAndVideos(){
    $role = session('role');
    $userId = session('account_id');

    if ($role === 'admin') {
       
        $TableGraphics = Graphic::all();
    } else {
  
        $TableGraphics = Graphic::where('account_id', $userId)->get();
    }
        return view('graphics.graphics', [
            'TableGraphics' => $TableGraphics,
        ]);
    }
    public function addGraphicsAndVideos(){

        return view ('graphics.addGraphics');
    }
    public function createGraphics ()
    {
         return $this->validateAndUploadMedia(request());   
    }
    
    
        
}

    


