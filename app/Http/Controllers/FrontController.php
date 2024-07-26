<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;

class FrontController extends Controller
{
    public function submitQuery(Request $request){

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|max:20',
            'msg_subject' => 'required|max:255',
            'message' => 'required:string'
        ]);
        try {
            $contact = new ContactUs();
            $contact->name = $request->input('name');
            $contact->email = $request->input('email');
            $contact->phone_number = $request->input('phone_number');
            $contact->msg_subject = $request->input('msg_subject');
            $contact->msg = $request->input('message');
            $contact->save();
    
            return response()->json(['message' => 'Contact message sent successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send contact message. Please try again later.'], 500);
        }
    }
}
