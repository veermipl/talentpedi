<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function addEvents(Request $request)
    {

        // Decode the JSON allData field
        $allData = json_decode($request->input('allData'), true);
        $event = new Event();
        $event->title = $allData['formData']['heading'];
        $event->description = $allData['quillInput']['description'];
        $event->e_date = date('d-m-Y', strtotime( $allData['formData']['date']));
        $event->status = $request['status'] == "true" ? "1" : "0";

        if ($request->hasFile('imageInput')) {
            $file = $request->file('imageInput');
            $filename  =  floor(microtime(true) * 1000) . '_' . $file->getClientOriginalName();
            $path = $file->move('storage/images/', $filename);
            $event->image = $filename;
        }

        $event->save();

        return response()->json([
            'message' => 'Event added successfully',
            'event' => $event,
        ], 201);
    }
    public function Events()
    {
        $event = Event::paginate(10);
        return response()->json($event);
    }
    public function changeStatus($id){
        $event = Event::find($id);
    
        if ($event) {
            $event->status = $event->status ? 0 : 1;
            $event->save();
        } else {
            return response()->json(['message' => 'event Detail not found'], 404);
        }
        return response()->json(['message' => 'status updated successfully', 'status' => $event->status]);
    }


    public function deleteEvent($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event detail not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Data deleted successfully'], 200);
    }
    public function editEvent($id){

        $compDetails = Event::find($id);

        return response()->json($compDetails);
    }
    public function posteditEvent(Request $request, $id){
        $allData = json_decode($request->input('formData'), true);
        $event = Event::find($id);
        if ($request->hasFile('imageInput')) {
            $file = $request->file('imageInput');
            $filename  =  floor(microtime(true) * 1000) . '_' . $file->getClientOriginalName();
            $path = $file->move('storage/images/', $filename);
            $event->image = $filename;
        }
    
        $event->title = $allData['title'];
        $event->description = $allData['description'];
        $event->e_date = date('Y-m-d', strtotime($allData['e_date']));
        $event->status = $request['status'] == "true" ? "1" : "0";
        $event->save();
        return response()->json(['message' => 'Data Updated successfully'], 200);

    }
}
