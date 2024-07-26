<?php

use App\Http\Controllers\authController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\CompetitionDetailController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FrontController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [authController::class, 'register']);
Route::get('/login', [authController::class, 'login'])->name('login');
Route::post('/login', [authController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {

    // profile routes
    Route::get('showImage', [authController::class, 'showImage']);
    Route::post('/uploadImage', [authController::class, 'uploadImage']);
    Route::get('/index', [authController::class, 'index']);
    Route::post('/updateProfile', [authController::class, 'updateProfile']);
    Route::post('/updatePassword', [authController::class, 'updatePassword']);

    // about us routes 
    Route::post('/aboutUs', [SettingController::class, 'aboutUs']);
    Route::get('/aboutUs', [SettingController::class, 'getAboutUs']);

    // privacy policy routes
    Route::get('/getPrivacyPolicy', [SettingController::class, 'getPrivacyPolicy']);
    Route::post('/postPrivacyPolicy', [SettingController::class, 'postPrivacyPolicy']);

    // return policy routes
    Route::get('/getReturnPolicy', [SettingController::class, 'getReturnPolicy']);
    Route::post('/postReturnPolicy', [SettingController::class, 'postReturnPolicy']);

    // Terms and conditions routes
    Route::get('/getTermsAndConditions', [SettingController::class, 'getTermsAndConditions']);
    Route::post('/postTermsAndConditions', [SettingController::class, 'postTermsAndConditions']);

    // Faq routes
    Route::get('/getFaqs', [SettingController::class, 'getFaqs']);
    Route::post('/postFaqs', [SettingController::class, 'postFaqs']);
    Route::get('/editFaqs/{id}', [SettingController::class, 'editFaqs']);
    Route::post('/editSubmitFaq/{id}', [SettingController::class, 'editSubmitFaq']);
    Route::post('/deletefaq/{id}', [SettingController::class, 'deletefaq']);
    Route::post('/faq/changeStatus/{id}', [SettingController::class, 'changeStatus']);


    Route::get('showLogo', [SettingController::class, 'showLogo']);

    // upload logo for profile
    Route::post('/uploadLogo', [SettingController::class, 'uploadLogo']);

    // company setting Routes
    Route::post('/updateCompanySetting', [SettingController::class, 'updateCompanySetting']);
    Route::get('/ShowComapanySetting', [SettingController::class, 'ShowComapanySetting']);

    // Smtp Setting Routes
    Route::post('/updateSmtpSetting', [SettingController::class, 'updateSmtpSetting']);
    Route::get('/ShowSmtpSetting', [SettingController::class, 'ShowSmtpSetting']);

    // banner Routes
    Route::post('/addBanner', [BannerController::class, 'addBanner']);
    Route::get('/Banner', [BannerController::class, 'Banner']);
    Route::get('/editBanner/{id}', [BannerController::class, 'editBanner']);
    Route::post('/editBanner/{id}', [BannerController::class, 'updateBanner']);
    Route::post('/deleteBanner/{id}', [BannerController::class, 'deleteBanner']);

    // competiton routes
    Route::get('/competition', [CompetitionController::class, 'competition']);
    Route::get('/selectCompetition', [CompetitionController::class, 'SelectCompetition']);
    Route::get('/competitionforDetails', [CompetitionController::class, 'competitionforDetails']);
    Route::post('/addCompetition', [CompetitionController::class, 'addCompetition']);
    Route::get('/editComptition/{id}', [CompetitionController::class, 'editComptition']);
    Route::post('/editSubmitComptition/{id}', [CompetitionController::class, 'editSubmitComptition']);
    Route::post('/deleteCompetition/{id}', [CompetitionController::class, 'deleteCompetition']);
    Route::post('/competition/changeStatus/{id}', [CompetitionController::class, 'changeStatus']);

    // competition details route
    Route::get('/competitionDetails', [CompetitionDetailController::class, 'competitionDetails']);
    Route::post('/addCompetitionDetails', [CompetitionDetailController::class, 'addCompetitionDetails']);
    Route::post('/deleteCompetitionDetails/{id}', [CompetitionDetailController::class, 'deleteCompetitionDetails']);
    Route::get('/editCompetitionDetails/{id}', [CompetitionDetailController::class, 'editCompetitionDetails']);
    Route::post('/editCompetitionDetails/{id}', [CompetitionDetailController::class, 'posteditCompetitionDetails']);
    Route::post('/competitionDetails/changeStatus/{id}', [CompetitionDetailController::class, 'changeStatus']);

    // Events route
    Route::get('/Events', [EventController::class, 'Events']);
    Route::post('/addEvents', [EventController::class, 'addEvents']);
    Route::post('/deleteEvent/{id}', [EventController::class, 'deleteEvent']);
    Route::get('/editEvent/{id}', [EventController::class, 'editEvent']);
    Route::post('/editEvent/{id}', [EventController::class, 'posteditEvent']);
    Route::post('/Events/changeStatus/{id}', [EventController::class, 'changeStatus']);
});


// frontend routes
Route::get('/Banner', [BannerController::class, 'Banner']);
Route::get('/category', [CompetitionController::class, 'category']);
Route::get('/getFaqs', [SettingController::class, 'getFaqs']);
Route::get('/allEvents/{id}', [CompetitionDetailController::class, 'allEventsFrontend']);
Route::get('/frontallEvents/{id}', [EventController::class, 'frontallEvents']);
Route::get('/frontcompetitionDetails/{id}', [CompetitionDetailController::class, 'frontcompetitionDetails']);
Route::post('/submitQuery', [FrontController::class, 'submitQuery']);

