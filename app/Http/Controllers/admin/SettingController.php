<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutUs;
use App\Models\Faq;
use App\Models\CompanySetting;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function getAboutUs()
    {
        $aboutUs = AboutUs::first();
        return response()->json($aboutUs);
    }
    public function aboutUs(Request $request)
    {

        $jsonString = $request->input('allData');
        $allData = json_decode($jsonString, true);
        $formData = $allData['formData'];
        $quillInput = $allData['quillInput'];

        $aboutUs = AboutUs::first();

        if (!$aboutUs) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $aboutUs->title = $formData['title'];
        $aboutUs->description = $quillInput['description'];
        $aboutUs->address = $formData['address'];
        $file = $request->file('imageInput');

        if (isset($file)) {
            $filename  =  floor(microtime(true) * 1000) . '_' . $request->file('imageInput')->getClientOriginalName();
            $path = $file->move('storage/images/', $filename);
            $aboutUs->update([
                'image' => $filename
            ]);
        }

        $aboutUs->save();

        return response()->json(['message' => 'data updated successfully']);
    }
    public function getPrivacyPolicy()
    {
        $privacy_policy = DB::table('privacy_policy')->get();
        return response()->json($privacy_policy);
    }
    public function postPrivacyPolicy(Request $request)
    {
        $quillInput = $request->input('quillInput');
        $return_policy = DB::table('return_policy')->first();
        DB::table('return_policy')
            ->where('id', $return_policy->id)
            ->update(['name' => $quillInput]);
        return response()->json(['message' => 'Return policy updated successfully']);
    }
    public function getReturnPolicy()
    {
        $privacy_policy = DB::table('return_policy')->get();
        return response()->json($privacy_policy);
    }
    public function postReturnPolicy(Request $request)
    {
        $quillInput = $request->input('quillInput');
        $return_policy = DB::table('return_policy')->first();
        DB::table('return_policy')
            ->where('id', $return_policy->id)
            ->update(['name' => $quillInput]);
        return response()->json(['message' => 'Return policy updated successfully']);
    }
    public function getTermsAndConditions()
    {
        $terms_and_conditions = DB::table('terms_and_conditions')->get();
        return response()->json($terms_and_conditions);
    }
    public function postTermsAndConditions(Request $request)
    {
        $quillInput = $request->input('quillInput');
        $terms_and_conditions = DB::table('terms_and_conditions')->first();
        DB::table('terms_and_conditions')
            ->where('id', $terms_and_conditions->id)
            ->update(['name' => $quillInput]);
        return response()->json(['message' => 'Data updated successfully']);    
    }
    public function getFaqs()
    {
        $faq = Faq::paginate(10);
        return response()->json([
            "faq" => $faq,
        ]);
    }
    public function postFaqs(Request $request)
    {
        $question = $request->input('question');
        $answer = $request->input('answer');
        $faq = Faq::create([
            'question' => $question,
            'answer' => $answer,
            'status' => 1
        ]);

        return response()->json(['message' => 'FAQ added successfully', 'faq' => $faq]);
    }
    public function uploadLogo(Request $request)
    {
        // Validate the request
        $rules = [
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
        $messages = [
            'file.max' => "The file must not be greater than 2 MB"
        ];
        // Validate the JSON data manually
        $validator = Validator::make($request->all(), $rules, $messages);

        // If validation fails, return a JSON response with errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle the file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename  =  floor(microtime(true) * 1000) . '_' . $request->file('file')->getClientOriginalName();
            $path = $file->move('storage/images/', $filename);
            $company_setting = CompanySetting::first();
            $company_setting->update([
                'logo' => $filename
            ]);
            return response()->json([
                'message' => 'Image uploaded successfully',
                'filename' => $filename
            ], 200);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }
    public function showLogo(Request $request)
    {
        $logo = CompanySetting::first()->logo;
        return response()->json(['logo' => $logo]);
    }
    public function updateCompanySetting(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'helpCenter' => 'nullable|string|max:255',
            'whatsappNumber' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:12',
            'email' => 'required|email|max:255',
            'facebookUrl' => 'nullable|url|max:255',
            'twitterUrl' => 'nullable|url|max:255',
            'instagramUrl' => 'nullable|url|max:255',
        ]);

        try {

            $company = CompanySetting::first();
            if (!$company) {
                return response()->json(['message' => 'Company not found'], 404);
            }

            $company->update([
                'title'           => $request->title,
                'address'         => $request->address,
                'help_center'     => $request->helpCenter,
                'whatspp_num'     => $request->whatsappNumber,
                'email_support'   => $request->email,
                'facebook'        => $request->facebookUrl,
                'twitter'         => $request->twitterUrl,
                'instagram'       => $request->instagramUrl,
            ]);

            return response()->json(['message' => 'Company settings updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating company settings'], 500);
        }
    }
    public function updateSmtpSetting(Request $request)
    {

        try {

            $company = SmtpSetting::first();
            if (!$company) {
                return response()->json(['message' => 'Data not found'], 404);
            }

            $company->update([
                'email'    => $request->email,
                'password' => $request->password,
                'host'     => $request->host,
                'port'     => $request->port,
            ]);

            return response()->json(['message' => 'Smtp settings updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating Smtp settings'], 500);
        }
    }
    public function ShowComapanySetting()
    {
        $ShowComapanySetting = CompanySetting::first();
        return response()->json(['data' => $ShowComapanySetting]);
    }
    public function ShowSmtpSetting()
    {
        $ShowComapanySetting = SmtpSetting::first();
        return response()->json(['data' => $ShowComapanySetting]);
    }
    public function editFaqs($id)
    {
        $faq = Faq::find($id);
        return response()->json(['data' => $faq]);
    }
    public function deletefaq($id)
    {
        $faq = Faq::find($id);
        $faq->delete();
        return response()->json(['data' => "data deleted successfully"]);
    }
    public function editSubmitFaq(Request $request, $id)
    {
        $question = $request->input('question');
        $answer = $request->input('answer');
        $faq = Faq::find($id);
        $faq->question = $question;
        $faq->answer = $answer;
        $faq->status = 1;
        $faq->save();
        return response()->json([
            'message' => 'Faq Updated successfully',
            'faq' => $faq,
        ], 201);
    }
    public function changeStatus($id){
        $Faq = Faq::find($id);
    
        if ($Faq) {
            $Faq->status = $Faq->status ? 0 : 1;
            $Faq->save();
        } else {
            return response()->json(['message' => 'Faq Detail not found'], 404);
        }
        return response()->json(['message' => 'status updated successfully', 'status' => $Faq->status]);
    }
}
