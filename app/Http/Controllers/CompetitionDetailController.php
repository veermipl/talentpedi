<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompetitionDetailController extends Controller
{
    public function competitionDetails()
    {
        $compDetails = Competition::select('competitions.name','competitions.com_desc','competition_details.c_id', 'competition_details.title','competition_details.status', 'competition_details.description', 'competition_details.image', 'competition_details.comp_date')
        ->Join('competition_details', 'competitions.id', '=', 'competition_details.comp_id')
        ->paginate(10);
        return response()->json($compDetails);
    }
    public function addCompetitionDetails(Request $request)
    {

        // Decode the JSON allData field
        $allData = json_decode($request->input('allData'), true);

        // Define validation rules for the JSON data
        $rules = [
            'heading' => 'required|max:255',
            'quillInput' => 'required',
            'category' => 'required|integer',
            'date' => 'required|date',
        ];
    
        // Define custom error messages for the JSON data (optional)
        $messages = [
            'heading.required' => 'The heading is required.',
            'quillInput.required' => 'The description is required.',
            'category.required' => 'The competition ID is required.',
            'date.required' => 'The date is required.',
        ];
    
        // Validate the JSON data manually
        $validator = Validator::make($allData, $rules, $messages);
    
        // If validation fails, return a JSON response with errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $competition = new CompetitionDetail();
        $competition->title = $allData['heading'];
        $competition->description = $allData['quillInput'];
        $competition->comp_id = $allData['category'];
        $competition->comp_date = date('d-m-Y', strtotime( $allData['date']));
        $competition->status = $request['status'] == "true" ? "1" : "0";

        if ($request->hasFile('imageInput')) {
            $file = $request->file('imageInput');
            $filename  =  floor(microtime(true) * 1000) . '_' . $file->getClientOriginalName();
            $path = $file->move('storage/images/', $filename);
            $competition->image = $filename;
        }

        $competition->save();

        return response()->json([
            'message' => 'Competition details added successfully',
            'competition' => $competition,
        ], 201);
    }
    public function deleteCompetitionDetails($id)
    {
        $deleteCompetitionDetails = CompetitionDetail::find($id);

        if (!$deleteCompetitionDetails) {
            return response()->json(['message' => 'Competition detail not found'], 404);
        }

        $deleteCompetitionDetails->delete();

        return response()->json(['message' => 'Data deleted successfully'], 200);
    }
    public function editCompetitionDetails($id){

        $compDetails = Competition::select('competitions.id','competitions.name','competition_details.c_id', 'competition_details.title', 'competition_details.description', 'competition_details.image', 'competition_details.comp_date', 'competition_details.status')
        ->leftJoin('competition_details', 'competitions.id', '=', 'competition_details.comp_id')
        ->where('c_id', $id)
        ->get();

        return response()->json($compDetails);
    }
    public function frontcompetitionDetails($id){

        $compDetails = Competition::select('competitions.id','competition_details.c_id', 'competition_details.title', 'competition_details.description', 'competition_details.image', 'competition_details.comp_date')
        ->leftJoin('competition_details', 'competitions.id', '=', 'competition_details.comp_id')
        ->where('c_id', $id)
        ->first();

        return response()->json($compDetails);
    }
    public function posteditCompetitionDetails(Request $request, $id){
        $allData = json_decode($request->input('allData'), true);
        $quillInput = json_decode($request->input('quillInput'), true);
        $competition = CompetitionDetail::find($id);
        if ($request->hasFile('imageInput')) {
            $file = $request->file('imageInput');
            $filename  =  floor(microtime(true) * 1000) . '_' . $file->getClientOriginalName();
            $path = $file->move('storage/images/', $filename);
            $competition->image = $filename;
        }
    
        $competition->title = $allData['formData']['title'];
        $competition->description = $quillInput['description'];
        $competition->comp_date = date('Y-m-d', strtotime($allData['formData']['comp_date']));
        $competition->comp_id = $allData['formData']['competition'];
        $competition->status = $request['status'] == "true" ? "1" : "0";
        $competition->save();
        return response()->json(['message' => 'Data Updated successfully'], 200);

    }
    public function changeStatus($id){
        $competition = CompetitionDetail::find($id);
    
        if ($competition) {
            $competition->status = $competition->status ? 0 : 1;
            $competition->save();
        } else {
            return response()->json(['message' => 'Competition Detail not found'], 404);
        }
        return response()->json(['message' => 'status updated successfully', 'status' => $competition->status]);
    }
    public function allEvents($id){
        $allEvents = Competition::select('competition_details.c_id','competition_details.c_id', 'competition_details.title','competition_details.status', 'competition_details.description', 'competition_details.image', 'competition_details.comp_date')
        ->Join('competition_details', 'competitions.id', '=', 'competition_details.comp_id')
        ->where('id', '=', $id)
        ->paginate(10);
        return response()->json($allEvents);
    }
    public function allEventsFrontend($id){
        $allEvents = Competition::select('competition_details.c_id','competition_details.c_id', 'competition_details.title','competition_details.status', 'competition_details.description', 'competition_details.image', 'competition_details.comp_date')
        ->Join('competition_details', 'competitions.id', '=', 'competition_details.comp_id')
        ->where('id', '=', $id)
        ->paginate(3);
        return response()->json($allEvents);
    }
    
}
