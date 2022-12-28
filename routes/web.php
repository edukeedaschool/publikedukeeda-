<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
    //return redirect('/login');
});*/


Auth::routes();  

Route::group(['middleware' => ['auth','superadmin']], function () {
Route::get('/states/list', [App\Http\Controllers\MasterDataController::class, 'listStates'])->name('stateslisting');
Route::get('/state/add', [App\Http\Controllers\MasterDataController::class, 'addState'])->name('addstate');
Route::post('/state/add', [App\Http\Controllers\MasterDataController::class, 'submitAddState'])->name('submitaddstate');
Route::get('/state/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editState'])->name('editstate');
Route::post('/state/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditState'])->name('submiteditstate');

Route::get('/districts/list', [App\Http\Controllers\MasterDataController::class, 'listDistricts'])->name('districtslisting');
Route::get('/district/add', [App\Http\Controllers\MasterDataController::class, 'addDistrict'])->name('adddistrict');
Route::post('/district/add', [App\Http\Controllers\MasterDataController::class, 'submitAddDistrict'])->name('submitadddistrict');
Route::get('/district/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editDistrict'])->name('editdistrict');
Route::post('/district/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditDistrict'])->name('submiteditdistrict');

Route::get('/mc1/list', [App\Http\Controllers\MasterDataController::class, 'listMC1'])->name('mc1listing');
Route::get('/mc1/add', [App\Http\Controllers\MasterDataController::class, 'addMC1'])->name('addmc1');
Route::post('/mc1/add', [App\Http\Controllers\MasterDataController::class, 'submitAddMC1'])->name('submitaddmc1');
Route::get('/mc1/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editMC1'])->name('editmc1');
Route::post('/mc1/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditMC1'])->name('submiteditmc1');

Route::get('/mc2/list', [App\Http\Controllers\MasterDataController::class, 'listMC2'])->name('mc2listing');
Route::get('/mc2/add', [App\Http\Controllers\MasterDataController::class, 'addMC2'])->name('addmc2');
Route::post('/mc2/add', [App\Http\Controllers\MasterDataController::class, 'submitAddMC2'])->name('submitaddmc2');
Route::get('/mc2/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editMC2'])->name('editmc2');
Route::post('/mc2/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditMC2'])->name('submiteditmc2');

Route::get('/districts/listing/{stateId}', [App\Http\Controllers\MasterDataController::class, 'getDistrictList'])->name('districtslisting1');
Route::get('/mc1/listing/{stateId}', [App\Http\Controllers\MasterDataController::class, 'getMC1List'])->name('mc1listing1');
Route::get('/mc2/listing/{districtId}', [App\Http\Controllers\MasterDataController::class, 'getMC2List'])->name('mc2listing1');
Route::get('/city-council/listing/{districtId}', [App\Http\Controllers\MasterDataController::class, 'getCityCouncilList'])->name('citycouncillisting1');
Route::get('/sub-district/listing/{districtId}', [App\Http\Controllers\MasterDataController::class, 'getSubDistrictList'])->name('subdistrictlisting1');
Route::get('/block/listing/{districtId}', [App\Http\Controllers\MasterDataController::class, 'getBlockList'])->name('blocklisting1');
Route::get('/village/listing/{subDistrictId}', [App\Http\Controllers\MasterDataController::class, 'getVillageList'])->name('villagelisting1');
Route::get('/ward/listing/{cityCouncilId}', [App\Http\Controllers\MasterDataController::class, 'getWardList'])->name('wardlisting1');
Route::get('/lac/listing/{districtId}', [App\Http\Controllers\MasterDataController::class, 'getLACList'])->name('laclisting1');
Route::get('/pc/listing/{districtId}', [App\Http\Controllers\MasterDataController::class, 'getPCList'])->name('pclisting1');

Route::get('/city-council/list', [App\Http\Controllers\MasterDataController::class, 'listCityCouncil'])->name('citycouncillisting');
Route::get('/city-council/add', [App\Http\Controllers\MasterDataController::class, 'addCityCouncil'])->name('addcitycouncil');
Route::post('/city-council/add', [App\Http\Controllers\MasterDataController::class, 'submitAddCityCouncil'])->name('submitaddcitycouncil');
Route::get('/city-council/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editCityCouncil'])->name('editcitycouncil');
Route::post('/city-council/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditCityCouncil'])->name('submiteditcitycouncil');

Route::get('/block/list', [App\Http\Controllers\MasterDataController::class, 'listBlock'])->name('blocklisting');
Route::get('/block/add', [App\Http\Controllers\MasterDataController::class, 'addBlock'])->name('addblock');
Route::post('/block/add', [App\Http\Controllers\MasterDataController::class, 'submitAddBlock'])->name('submitaddblock');
Route::get('/block/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editBlock'])->name('editblock');
Route::post('/block/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditBlock'])->name('submiteditblock');

Route::get('/sub-district/list', [App\Http\Controllers\MasterDataController::class, 'listSubDistrict'])->name('subdistrictlisting');
Route::get('/sub-district/add', [App\Http\Controllers\MasterDataController::class, 'addSubDistrict'])->name('addsubdistrict');
Route::post('/sub-district/add', [App\Http\Controllers\MasterDataController::class, 'submitAddSubDistrict'])->name('submitaddsubdistrict');
Route::get('/sub-district/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editSubDistrict'])->name('editsubdistrict');
Route::post('/sub-district/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditSubDistrict'])->name('submiteditsubdistrict');

Route::get('/ward/list', [App\Http\Controllers\MasterDataController::class, 'listWard'])->name('wardlisting');
Route::get('/ward/add', [App\Http\Controllers\MasterDataController::class, 'addWard'])->name('addward');
Route::post('/ward/add', [App\Http\Controllers\MasterDataController::class, 'submitAddWard'])->name('submitaddward');
Route::get('/ward/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editWard'])->name('editward');
Route::post('/ward/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditWard'])->name('submiteditward');

Route::get('/village/list', [App\Http\Controllers\MasterDataController::class, 'listVillage'])->name('villagelisting');
Route::get('/village/add', [App\Http\Controllers\MasterDataController::class, 'addVillage'])->name('addvillage');
Route::post('/village/add', [App\Http\Controllers\MasterDataController::class, 'submitAddVillage'])->name('submitaddvillage');
Route::get('/village/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editVillage'])->name('editvillage');
Route::post('/village/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditVillage'])->name('submiteditvillage');

Route::get('/postal-code/list', [App\Http\Controllers\MasterDataController::class, 'listPostalCode'])->name('postalcodelisting');
Route::get('/postal-code/add', [App\Http\Controllers\MasterDataController::class, 'addPostalCode'])->name('addpostalcode');
Route::post('/postal-code/add', [App\Http\Controllers\MasterDataController::class, 'submitAddPostalCode'])->name('submitaddpostalcode');
Route::get('/postal-code/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editPostalCode'])->name('editpostalcode');
Route::post('/postal-code/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditPostalCode'])->name('submiteditpostalcode');
Route::get('/postal-code/data/{postalCode}', [App\Http\Controllers\MasterDataController::class, 'getPostalCodeData'])->name('getpostalcodedata');

Route::get('/political-party/list', [App\Http\Controllers\MasterDataController::class, 'listPoliticalParty'])->name('politicalpartylisting');
Route::get('/political-party/add', [App\Http\Controllers\MasterDataController::class, 'addPoliticalParty'])->name('addpoliticalparty');
Route::post('/political-party/add', [App\Http\Controllers\MasterDataController::class, 'submitAddPoliticalParty'])->name('submitaddpoliticalparty');
Route::get('/political-party/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editPoliticalParty'])->name('editpoliticalparty');
Route::post('/political-party/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditPoliticalParty'])->name('submiteditpoliticalparty');

Route::get('/la-constituency/list', [App\Http\Controllers\MasterDataController::class, 'listLAConstituency'])->name('laconstituencylisting');
Route::get('/la-constituency/add', [App\Http\Controllers\MasterDataController::class, 'addLAConstituency'])->name('addlaconstituency');
Route::post('/la-constituency/add', [App\Http\Controllers\MasterDataController::class, 'submitAddLAConstituency'])->name('submitaddlaconstituency');
Route::get('/la-constituency/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editLAConstituency'])->name('editlaconstituency');
Route::post('/la-constituency/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditLAConstituency'])->name('submiteditlaconstituency');

Route::get('/pa-constituency/list', [App\Http\Controllers\MasterDataController::class, 'listPAConstituency'])->name('paconstituencylisting');
Route::get('/pa-constituency/add', [App\Http\Controllers\MasterDataController::class, 'addPAConstituency'])->name('addpaconstituency');
Route::post('/pa-constituency/add', [App\Http\Controllers\MasterDataController::class, 'submitAddPAConstituency'])->name('submitaddpaconstituency');
Route::get('/pa-constituency/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editPAConstituency'])->name('editpaconstituency');
Route::post('/pa-constituency/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditPAConstituency'])->name('submiteditpaconstituency');

Route::get('/govt-department/list', [App\Http\Controllers\MasterDataController::class, 'listGovernmentDepartment'])->name('govtdepartmentlisting');
Route::get('/govt-department/add', [App\Http\Controllers\MasterDataController::class, 'addGovernmentDepartment'])->name('addgovtdepartment');
Route::post('/govt-department/add', [App\Http\Controllers\MasterDataController::class, 'submitAddGovernmentDepartment'])->name('submitaddgovtdepartment');
Route::get('/govt-department/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editGovernmentDepartment'])->name('editgovtdepartment');
Route::post('/govt-department/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditGovernmentDepartment'])->name('submiteditgovtdepartment');

Route::get('/non-profit-organization/list', [App\Http\Controllers\MasterDataController::class, 'listNonProfitOrganization'])->name('nonprofitorganizationlisting');
Route::get('/non-profit-organization/add', [App\Http\Controllers\MasterDataController::class, 'addNonProfitOrganization'])->name('addnonprofitorganization');
Route::post('/non-profit-organization/add', [App\Http\Controllers\MasterDataController::class, 'submitAddNonProfitOrganization'])->name('submitaddnonprofitorganization');
Route::get('/non-profit-organization/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editNonProfitOrganization'])->name('editnonprofitorganization');
Route::post('/non-profit-organization/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditNonProfitOrganization'])->name('submiteditnonprofitorganization');

Route::get('/elected-official-position/list', [App\Http\Controllers\MasterDataController::class, 'listElectedOfficialPosition'])->name('govtelectedofficialpositionlisting');
Route::get('/elected-official-position/add', [App\Http\Controllers\MasterDataController::class, 'addElectedOfficialPosition'])->name('addgovtelectedofficialposition');
Route::post('/elected-official-position/add', [App\Http\Controllers\MasterDataController::class, 'submitAddElectedOfficialPosition'])->name('submitaddgovtelectedofficialposition');
Route::get('/elected-official-position/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editElectedOfficialPosition'])->name('editgovtelectedofficialposition');
Route::post('/elected-official-position/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditElectedOfficialPosition'])->name('submiteditgovtelectedofficialposition');

Route::get('/political-party-official-position/list', [App\Http\Controllers\MasterDataController::class, 'listPoliticalPartyOfficialPosition'])->name('govtpoliticalpartyofficialpositionlisting');
Route::get('/political-party-official-position/add', [App\Http\Controllers\MasterDataController::class, 'addPoliticalPartyOfficialPosition'])->name('addgovtpoliticalpartyofficialposition');
Route::post('/political-party-official-position/add', [App\Http\Controllers\MasterDataController::class, 'submitAddPoliticalPartyOfficialPosition'])->name('submitaddgovtpoliticalpartyofficialposition');
Route::get('/political-party-official-position/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editPoliticalPartyOfficialPosition'])->name('editgovtpoliticalpartyofficialposition');
Route::post('/political-party-official-position/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditPoliticalPartyOfficialPosition'])->name('submiteditgovtpoliticalpartyofficialposition');

Route::get('/group/list', [App\Http\Controllers\MasterDataController::class, 'listGroup'])->name('grouplisting');
Route::get('/group/add', [App\Http\Controllers\MasterDataController::class, 'addGroup'])->name('addgroup');
Route::post('/group/add', [App\Http\Controllers\MasterDataController::class, 'submitAddGroup'])->name('submitaddgroup');
Route::get('/group/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editGroup'])->name('editgroup');
Route::post('/group/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditGroup'])->name('submiteditgroup');

Route::get('/sub-group/list', [App\Http\Controllers\MasterDataController::class, 'listSubGroup'])->name('subgrouplisting');
Route::get('/sub-group/add', [App\Http\Controllers\MasterDataController::class, 'addSubGroup'])->name('addsubgroup');
Route::post('/sub-group/add', [App\Http\Controllers\MasterDataController::class, 'submitAddSubGroup'])->name('submitaddsubgroup');
Route::get('/sub-group/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editSubGroup'])->name('editsubgroup');
Route::post('/sub-group/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditSubGroup'])->name('submiteditsubgroup');
Route::get('/office-belongs-to-data/{subGroupId}', [App\Http\Controllers\MasterDataController::class, 'getOfficeBelongsToData'])->name('officebelongstodata');

Route::get('/submission-purpose/list', [App\Http\Controllers\MasterDataController::class, 'listSubmissionPurpose'])->name('submissionpurposelisting');
Route::get('/submission-purpose/add', [App\Http\Controllers\MasterDataController::class, 'addSubmissionPurpose'])->name('addsubmissionpurpose');
Route::post('/submission-purpose/add', [App\Http\Controllers\MasterDataController::class, 'submitAddSubmissionPurpose'])->name('submitaddsubmissionpurpose');
Route::get('/submission-purpose/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editSubmissionPurpose'])->name('editsubmissionpurpose');
Route::post('/submission-purpose/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditSubmissionPurpose'])->name('submiteditsubmissionpurpose');

Route::get('/submission-type/list', [App\Http\Controllers\MasterDataController::class, 'listSubmissionType'])->name('submissiontypelisting');
Route::get('/submission-type/add', [App\Http\Controllers\MasterDataController::class, 'addSubmissionType'])->name('addsubmissiontype');
Route::post('/submission-type/add', [App\Http\Controllers\MasterDataController::class, 'submitAddSubmissionType'])->name('submitaddsubmissiontype');
Route::get('/submission-type/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editSubmissionType'])->name('editsubmissiontype');
Route::post('/submission-type/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditSubmissionType'])->name('submiteditsubmissiontype');

Route::get('/review-level/list', [App\Http\Controllers\MasterDataController::class, 'listReviewLevel'])->name('reviewlevellisting');
Route::get('/review-level/add', [App\Http\Controllers\MasterDataController::class, 'addReviewLevel'])->name('addreviewlevel');
Route::post('/review-level/add', [App\Http\Controllers\MasterDataController::class, 'submitAddReviewLevel'])->name('submitaddreviewlevel');
Route::get('/review-level/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'editReviewLevel'])->name('editreviewlevel');
Route::post('/review-level/edit/{Id}', [App\Http\Controllers\MasterDataController::class, 'submitEditReviewLevel'])->name('submiteditreviewlevel');
Route::post('/master-data/bulk/update', [App\Http\Controllers\MasterDataController::class, 'updateBulkMasterData'])->name('updatebulkmasterdata');

Route::get('/subscriber/list', [App\Http\Controllers\SubscriberController::class, 'listSubscriber'])->name('subscriberlisting');
Route::get('/subscriber/add', [App\Http\Controllers\SubscriberController::class, 'addSubscriber'])->name('addsubscriber');
Route::post('/subscriber/add', [App\Http\Controllers\SubscriberController::class, 'submitAddSubscriber'])->name('submitaddsubscriber');
Route::get('/subscriber/edit/{Id}', [App\Http\Controllers\SubscriberController::class, 'editSubscriber'])->name('editsubscriber');
Route::post('/subscriber/edit/{Id}', [App\Http\Controllers\SubscriberController::class, 'submitEditSubscriber'])->name('submiteditsubscriber');
Route::post('/subscriber/update', [App\Http\Controllers\SubscriberController::class, 'updateSubscriber'])->name('updatesubscriber');

Route::get('/user/list', [App\Http\Controllers\UserController::class, 'listUser'])->name('userlisting');
Route::get('/user/add', [App\Http\Controllers\UserController::class, 'addUser'])->name('adduser');
Route::post('/user/add', [App\Http\Controllers\UserController::class, 'submitAddUser'])->name('submitadduser');
Route::get('/user/edit/{Id}', [App\Http\Controllers\UserController::class, 'editUser'])->name('edituser');
Route::post('/user/edit/{Id}', [App\Http\Controllers\UserController::class, 'submitEditUser'])->name('submitedituser');
Route::post('/user/update', [App\Http\Controllers\UserController::class, 'updateUser'])->name('updateuser');
Route::get('/user/data/{email}', [App\Http\Controllers\UserController::class, 'getUserData'])->name('getuserdata');
});

Route::group(['middleware' => ['auth','subscriber']], function () {
Route::get('/subscriber/review-data/view', [App\Http\Controllers\SubscriberController::class, 'viewSubscriberReviewData'])->name('viewreviewdata');
Route::get('/subscriber/review-data/edit', [App\Http\Controllers\SubscriberController::class, 'editSubscriberReviewData'])->name('editreviewdata');
Route::post('/subscriber/review-data/edit', [App\Http\Controllers\SubscriberController::class, 'submitEditSubscriberReviewData'])->name('submiteditreviewdata');
Route::get('/subscriber/review-range/data/{Id}', [App\Http\Controllers\SubscriberController::class, 'getReviewRangeData'])->name('getreviewrangedata');

Route::get('/team-designation/list', [App\Http\Controllers\TeamController::class, 'listTeamDesignation'])->name('teamdesignationlisting');
Route::get('/team-designation/add', [App\Http\Controllers\TeamController::class, 'addTeamDesignation'])->name('addteamdesignation');
Route::post('/team-designation/add', [App\Http\Controllers\TeamController::class, 'submitAddTeamDesignation'])->name('submitaddteamdesignation');
Route::get('/team-designation/edit/{Id}', [App\Http\Controllers\TeamController::class, 'editTeamDesignation'])->name('editteamdesignation');
Route::post('/team-designation/edit/{Id}', [App\Http\Controllers\TeamController::class, 'submitEditTeamDesignation'])->name('submiteditteamdesignation');
Route::post('/team-designation/update', [App\Http\Controllers\TeamController::class, 'updateTeamDesignation'])->name('updateteamdesignation');
Route::get('/team-designation/data/{Id}', [App\Http\Controllers\TeamController::class, 'getTeamDesignationData'])->name('getteamdesignationdata');

Route::get('/team-member/list', [App\Http\Controllers\TeamController::class, 'listTeamMember'])->name('teammemberlisting');
Route::get('/team-member/add', [App\Http\Controllers\TeamController::class, 'addTeamMember'])->name('addteammember');
Route::post('/team-member/add', [App\Http\Controllers\TeamController::class, 'submitAddTeamMember'])->name('submitaddteammember');
Route::get('/team-member/edit/{Id}', [App\Http\Controllers\TeamController::class, 'editTeamMember'])->name('editteammember');
Route::post('/team-member/edit/{Id}', [App\Http\Controllers\TeamController::class, 'submitEditTeamMember'])->name('submiteditteammember');
Route::post('/team-member/update', [App\Http\Controllers\TeamController::class, 'updateTeamMember'])->name('updateteammember');

Route::get('/review-official/list', [App\Http\Controllers\SubscriberController::class, 'listReviewOfficial'])->name('reviewofficiallisting');
Route::get('/review-official/add', [App\Http\Controllers\SubscriberController::class, 'addReviewOfficial'])->name('addreviewofficial');
Route::post('/review-official/add', [App\Http\Controllers\SubscriberController::class, 'submitAddReviewOfficial'])->name('submitaddreviewofficial');
Route::get('/review-official/edit/{Id}', [App\Http\Controllers\SubscriberController::class, 'editReviewOfficial'])->name('editreviewofficial');
Route::post('/review-official/edit/{Id}', [App\Http\Controllers\SubscriberController::class, 'submitEditReviewOfficial'])->name('submiteditreviewofficial');
Route::post('/review-official/update', [App\Http\Controllers\SubscriberController::class, 'updateReviewOfficial'])->name('updatereviewofficial');
Route::get('/subscriber-review/data/{Id}', [App\Http\Controllers\SubscriberController::class, 'getSubscriberReviewData'])->name('getsubscriberreviewdata');
Route::get('/user/data/{email}', [App\Http\Controllers\UserController::class, 'getUserData'])->name('getuserdata');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/user/change-password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('changepassword');
    Route::post('/user/change-password', [App\Http\Controllers\UserController::class, 'submitChangePassword'])->name('submitchangepassword');
    Route::get('/user/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
});


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::post('/user/login', [App\Http\Controllers\UserController::class, 'submitLogin'])->name('loginsubmit');
Route::get('/user/signup', [App\Http\Controllers\UserController::class, 'signup'])->name('signup');
Route::post('/user/signup', [App\Http\Controllers\UserController::class, 'submitSignup'])->name('submitsignup');
Route::get('/access-denied', [App\Http\Controllers\HomeController::class, 'accessDenied'])->name('accessdenied');
Route::any('/api-data', [App\Http\Controllers\UserController::class, 'getAPIData'])->name('getapidata');