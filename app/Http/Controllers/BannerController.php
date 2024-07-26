<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Redis;

class BannerController extends Controller
{

    public function addBanner(Request $request)
    {

        $allData = $request->allData;
        $data = json_decode($allData, true);
        $heading = $data['formData']['heading'];
        $description = $data['formData']['description'];
        $file = $request->file('imageInput');
        $filename  =  floor(microtime(true) * 1000) . '_' . $file->getClientOriginalName();
        $path = $file->move('storage/images/', $filename);
        $banner = new Banner();
        $banner->heading = $heading;
        $banner->description = $description;
        $banner->image = $filename;
        $banner->status = 1;
        $banner->save();
        return response()->json(['message' => 'Banner created successfully'], 201);
    }
    public function Banner(){
        $banner = Banner::where('status', '=', '1')->get();
        return response()->json($banner);
    }
    public function editBanner(Request $request, $id){
        $banner = Banner::find($id);
        return response()->json($banner);
    }
    public function deleteBanner(Request $request, $id){
        $banner = Banner::find($id);
        $banner->delete();
        return response()->json(['message' => 'data deleted successfully']);
    }
    public function updateBanner(Request $request, $id){
        $allData = $request->allData;
        $data = json_decode($allData, true);
        $heading = $data['formData']['heading'];
        $description = $data['formData']['description'];
        $banner = Banner::find($id);
        if($request->hasFile('imageInput')){
        $file = $request->file('imageInput');
        $filename  =  floor(microtime(true) * 1000) . '_' . $file->getClientOriginalName();
        $path = $file->move('storage/images/', $filename); 
        $banner = $banner->update([
            'heading' => $heading,
            'description' => $description,
            'image' => $filename
        ]);
        }else{
            $banner = $banner->update([
                'heading' => $heading,
                'description' => $description,
            ]);
        }
        return response()->json(['message' => 'Banner Updated successfully'], 201);

    }
}
