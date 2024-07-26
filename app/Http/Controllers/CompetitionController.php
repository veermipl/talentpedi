<?php

namespace App\Http\Controllers;
use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function competition(){
        $competition = Competition::paginate(10);
        return response()->json($competition);
    }
    public function SelectCompetition(){
        $competition = Competition::where('status', '1')->get();
        return response()->json($competition);
    }
    public function category(){
        $competition = Competition::where('status', '1')->get();
        return response()->json($competition);
    }
    public function competitionforDetails(){
        $competition = Competition::where('status', '=', 1)->get();
        return response()->json($competition);
    }
    public function editComptition($id){
        $competition = Competition::find($id);
        return response()->json(['data' => $competition]);
    }
    public function deleteCompetition(Request $request, $id){
        $competition = Competition::find($id);
        $competition->delete();
        return response()->json(['message'=> 'data deleted successfully']);
    }
    public function addCompetition(Request $request){
        $allData = json_decode($request->input('data'), true);
        $comp_name = $allData['competition'];
        $comp_desc = $allData['competition_description'];
        $comp_status = $request['status'] == "true" ? "1" : "0";
        $competition = new Competition();
        $competition->name = $comp_name;
        $competition->com_desc = $comp_desc;
        $competition->status = $comp_status;
        if ($request->hasFile('imageInput')) {
            $file = $request->file('imageInput');
            $filename  =  floor(microtime(true) * 1000) . '_' . $file->getClientOriginalName();
            $path = $file->move('storage/images/', $filename);
            $competition->image = $filename;
        }

        $competition->save();
        return response()->json([
            'message' => 'Competition added successfully',
            'competition' => $competition,
        ], 201);
    }
    public function editSubmitComptition(Request $request, $id){

        $competition = Competition::find($id);
        $allData = json_decode($request->input('data'), true);
        $comp_name = $allData['competition'];
        $comp_desc = $allData['competition_description'];
        $status = $allData['status'];
        $competition->name = $comp_name;
        $competition->com_desc = $comp_desc;
        $competition->status = $status;
        if ($request->hasFile('imageInput')) {
            $file = $request->file('imageInput');
            $filename  =  floor(microtime(true) * 1000) . '_' . $file->getClientOriginalName();
            $path = $file->move('storage/images/', $filename);
            $competition->image = $filename;
        }

        $competition->save();
        return response()->json([
            'message' => 'Competition Updated successfully',
            'competition' => $competition,
        ], 201);
    }
    public function changeStatus($id){
        $competition = Competition::find($id);
    
        if ($competition) {
            $competition->status = $competition->status ? 0 : 1;
            $competition->save();
        } else {
            return response()->json(['message' => 'Competition not found'], 404);
        }
        return response()->json(['message' => 'Competition status updated successfully', 'status' => $competition->status]);
    }
    
}
