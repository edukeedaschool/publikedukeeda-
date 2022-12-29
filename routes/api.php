<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [App\Http\Controllers\DataApiController::class, 'login'])->name('login');

Route::group(['middleware' => ['data_api']], function () {
    Route::get('/country/list', [App\Http\Controllers\DataApiController::class, 'getCountryList'])->name('getcountrylist');
    Route::get('/state/list', [App\Http\Controllers\DataApiController::class, 'getStateList'])->name('getstatelist');
    Route::get('/district/list/{stateId}', [App\Http\Controllers\DataApiController::class, 'getDistrictList'])->name('getdistrictlist');
    Route::get('/municipal-corporation/list', [App\Http\Controllers\DataApiController::class, 'getMunicipalCorporationList'])->name('getmunicipalcorporationlist');
    Route::get('/municipality/list', [App\Http\Controllers\DataApiController::class, 'getMunicipalityList'])->name('getmunicipalitylist');
    Route::get('/city-council/list', [App\Http\Controllers\DataApiController::class, 'getCityCouncilList'])->name('getcitycouncillist');
    Route::get('/block/list', [App\Http\Controllers\DataApiController::class, 'getBlockList'])->name('getblocklist');
    Route::get('/sub-district/list/{districtId}', [App\Http\Controllers\DataApiController::class, 'getSubDistrictList'])->name('getsubdistrictlist');
    Route::get('/political-party/list', [App\Http\Controllers\DataApiController::class, 'getPoliticalPartyList'])->name('getpoliticalpartylist');
    Route::get('/legislative-assembly/list', [App\Http\Controllers\DataApiController::class, 'getLegislativeAssemblyList'])->name('getlegislativeassemblylist');
    Route::get('/parliamentary-assembly/list', [App\Http\Controllers\DataApiController::class, 'getParliamentaryAssemblyList'])->name('getparliamentaryassemblylist');
    Route::get('/erop/list', [App\Http\Controllers\DataApiController::class, 'getEROPList'])->name('geteroplist');
    Route::get('/ppop/list', [App\Http\Controllers\DataApiController::class, 'getPPOPList'])->name('getppoplist');
    Route::get('/government-department/list', [App\Http\Controllers\DataApiController::class, 'getGovernmentDepartmentList'])->name('getgovernmentdepartmentlist');
    Route::get('/non-profit-organization/list', [App\Http\Controllers\DataApiController::class, 'getNonProfitOrganizationList'])->name('getnonprofitorganizationList');
    Route::get('/group/list', [App\Http\Controllers\DataApiController::class, 'getGroupList'])->name('getgrouplist');
    Route::get('/sub-group/list', [App\Http\Controllers\DataApiController::class, 'getSubGroupList'])->name('getsubgrouplist');
    Route::get('/submission-purpose/list', [App\Http\Controllers\DataApiController::class, 'getSubmissionPurposeList'])->name('getsubmissionpurposeList');
    Route::get('/submission-type/list', [App\Http\Controllers\DataApiController::class, 'getSubmissionTypeList'])->name('getsubmissiontypeList');
    Route::get('/review-level/list', [App\Http\Controllers\DataApiController::class, 'getReviewLevelList'])->name('getreviewlevelList');
    Route::get('/qualification/list', [App\Http\Controllers\DataApiController::class, 'getQualificationList'])->name('getqualificationlist');
    Route::get('/village/list/{subDistrictId}', [App\Http\Controllers\DataApiController::class, 'getVillageList'])->name('getvillagelist');
    
    Route::post('/signup', [App\Http\Controllers\DataApiController::class, 'signup'])->name('signup');
    Route::post('/change-password', [App\Http\Controllers\DataApiController::class, 'changePassword'])->name('changepassword');
    Route::get('/profile/data/{userId}', [App\Http\Controllers\DataApiController::class, 'getProfileData'])->name('getprofiledata');
    Route::post('/profile/update', [App\Http\Controllers\DataApiController::class, 'updateProfileData'])->name('updateprofiledata');
});

Route::post('/profile/update-image', [App\Http\Controllers\DataApiController::class, 'updateProfileImage'])->name('updateprofileimage');