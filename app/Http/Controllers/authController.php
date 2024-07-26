<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class authController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'user'    => $user,
        ]);
    }
    public function updatePassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8',
        ]);

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => ['current_password' => 'Current password does not match']], 400);
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => 'Password updated successfully'], 200);
    }
    public function updateProfile(Request $request){


        $request->validate([
            'Username' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'phoneNumber' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:12',
            'zipCode' => 'required|regex:/\b\d{5}\b/'
        ]);

        $user = Auth::user();
        $user->update([
         'username' => $request->Username,
         'phone_number' => $request->phoneNumber,
         'address' => $request->address,
         'state' => $request->state,
         'zipCode' => $request->zipCode
        ]); 
        return response()->json([
            'status' => true,
            'message' => "Information updated successfully..."
        ]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        return response()->json([
            'success' => 'true',
            'user'    => $user,
            'message' => 'Registration has been successfully'
        ]);
    }
    public function login()
    {
        if ($user = Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $getToken = $user->createToken('token')->plainTextToken;
            return response()->json([
                'status' => true,
                'token' => $getToken,
                'user'    => $user
            ]);
        } else {
            return response()->json(['error' => 'Incorrect credentials'], 401);
        }
    }
    public function showImage(Request $request){
        $profile_pic = Auth::user()->profile_pic;
        return response()->json(['profile_pic' => $profile_pic]);
    }
    public function uploadImage(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Handle the file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename  =  floor(microtime(true) * 1000).'_'.$request->file('file')->getClientOriginalName();
            $path = $file->move('storage/images/', $filename);
            $user = Auth::user();
            $user->update([
                'profile_pic' => $filename
            ]);
            return response()->json([
                'message' => 'Image uploaded successfully',
                'filename' => $filename
            ], 200);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }
}
