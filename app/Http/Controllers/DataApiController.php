<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\StateList;
use App\Models\CountryList;
use App\Models\DistrictList;
use App\Models\Mc1List;
use App\Models\Mc2List;
use App\Models\CityCouncil;
use App\Models\BlockList;
use App\Models\SubDistrictList;
use App\Models\WardList;
use App\Models\VillageList;
use App\Models\PostalCodeList;
use App\Models\PoliticalPartyList;
use App\Models\LegislativeAssemblyConstituency;
use App\Models\ParliamentaryConstituency;
use App\Models\GovernmentDepartment;
use App\Models\NonProfitOrganization;
use App\Models\ElectedOfficialPosition;
use App\Models\PoliticalPartyOfficialPosition;
use App\Models\GroupList;
use App\Models\SubGroupList;
use App\Models\SubmissionPurposeList;
use App\Models\SubmissionTypeList;
use App\Models\ReviewLevel;
use App\Models\RepresentationAreaList;
use App\Models\Submissions;
use App\Models\SubscriberList;
use App\Models\SubscriberPackage;
use App\Models\TeamMembers;
use App\Models\SubscriberFollowers;
use App\Helpers\CommonHelper;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class DataApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
       $this->REC_PER_PAGE = 50;
    }
    
    function login(Request $request){
        try{ 
            $data = $request->all();
            
            $content_type_header = $request->header('Content-Type');//print_r($content_type_header);exit;
            if(empty($content_type_header) || strtolower($content_type_header) != 'application/json' ){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Invalid Content-Type Header', 'errors' => 'Invalid Content-Type Header'),200);
            }

            $validateionRules = array('email'=>'required|email','password'=>'required');
            $attributes = array();
            
            $validator = Validator::make($data,$validateionRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Email and Password are Required Fields', 'errors' => $validator->errors()),200);
            }	
            
            if(Auth::attempt(['email' =>$data['email'], 'password' =>$data['password'],'status'=>1,'is_deleted'=>0])) {
                // Fetch user details from email
                $user_data = User::where('email',trim($data['email']))->wherein('user_role',[1,2,3,4])->where('is_deleted',0)->where('status',1)->select('id','name','api_token','api_token_created_at','email','user_name','user_role')->first();
                
                if($user_data->user_role == 4){
                    if(!empty($user_data->api_token) && (time()-strtotime($user_data->api_token_created_at))/3600 <= 240){
                        $api_token = $user_data->api_token;
                    }else{
                        $api_token = md5(uniqid($user_data->id, true));
                        $updateArray = array('api_token'=>$api_token,'api_token_created_at'=>date('Y/m/d H:i:s'));
                        User::where('id',$user_data->id)->update($updateArray);
                        $user_data = User::where('id',$user_data->id)->select('id','name','api_token','api_token_created_at','email','user_name','user_role')->first();
                    }
                }else{
                    unset($user_data->api_token);
                    unset($user_data->api_token_created_at);
                }
                
                unset($user_data->user_role);
                
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Authenticated','user_data'=>$user_data),200);
            }else{
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => 'Incorrect login credentials'),200);
            }
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage().', '.$e->getLine(),'message'=>'Error in Processing Request'),200);
        }
    }
    
    function signup(Request $request){
        try{ 
            $data = $request->all();
            $errors = [];
            
            $validationRules = array('name'=>'required|min:2','email'=>'required|email','password'=>'required|min:6|max:100|confirmed','user_name'=>'required|min:6','password_confirmation'=>'required');
            $attributes = array();
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Name, Email, Username, Password Errors', 'errors' => $validator->errors()),200);
            }	
            
            $email_exists = User::where('email',trim($data['email']))->where('is_deleted',0)->select('id')->first();
            if(!empty($email_exists)){
                $errors['email'] = ['User already exists with Email Address'];
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => 'User already exists with Email Address', 'errors' =>$errors),200);
            }
            
            $username_exists = User::where('user_name',trim($data['user_name']))->where('is_deleted',0)->select('id')->first();
            if(!empty($username_exists)){
                $errors['user_name'] = ['User already exists with Username'];
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => 'User already exists with Username', 'errors' =>$errors),200);
            }
            
            $insertArray = array('name'=>trim($data['name']),'email'=>trim($data['email']),'user_name'=>trim($data['user_name']),'user_role'=>3,'password'=>Hash::make(trim($data['password'])));
            
            $user_data = User::create($insertArray);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'User added','user_data'=>$user_data),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage().', '.$e->getLine(),'message'=>'Error in Processing Request'),200);
        }
    }
    
    function changePassword(Request $request){
        try{ 
            $data = $request->all();
            $errors = [];
            
            $validationRules = array('email'=>'required|email','current_password'=>'required|min:6|max:100','new_password'=>'required|min:6|max:100|confirmed','new_password_confirmation'=>'required');
            $attributes = array();
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation Errors', 'errors' => $validator->errors()),200);
            }	
            
            $user_data = User::where('email',trim($data['email']))->where('is_deleted',0)->where('status',1)->select('id','email','password')->first();
            
            if(empty($user_data)){
                $errors['email'] = ['User does not exists with Email Address'];
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => $errors['email'], 'errors' =>$errors),200);
            }
            
            //if(Hash::make(trim($data['current_password'])) != $user_data->password){
            if(!Auth::attempt(['email' =>$data['email'], 'password' =>$data['current_password'],'status'=>1,'is_deleted'=>0])) {    
                $errors['current_password'] = ['Incorrect Current Password '];
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => $errors['current_password'], 'errors' =>$errors),200);
            }
            
            $updateArray = ['password'=>Hash::make(trim($data['new_password']))];
            
            User::where('id',$user_data->id)->update($updateArray);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Password updated successfully'),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage().', '.$e->getLine(),'message'=>'Error in Processing Request'),200);
        }
    }
    
    function getProfileData(Request $request,$user_id){
        try{ 
            $data = $request->all();
            
            $user_data = User::leftJoin('country_list as cl',function($join){$join->on('cl.id','=','users.country')->where('cl.is_deleted','=','0')->where('cl.status','=','1');})                
            ->leftJoin('state_list as sl',function($join){$join->on('sl.id','=','users.state')->where('sl.is_deleted','=','0')->where('sl.status','=','1');})
            ->leftJoin('district_list as dl',function($join){$join->on('dl.id','=','users.district')->where('dl.is_deleted','=','0')->where('dl.status','=','1');})
            ->leftJoin('sub_district_list as sd',function($join){$join->on('sd.id','=','users.sub_district')->where('sd.is_deleted','=','0')->where('sd.status','=','1');})
            ->leftJoin('village_list as vl',function($join){$join->on('vl.id','=','users.village')->where('vl.is_deleted','=','0')->where('vl.status','=','1');})
            ->where('users.id',trim($user_id))
            ->where('users.is_deleted',0)
            ->where('users.status',1)
            ->select('users.id','users.name','users.email','users.user_name','users.official_name','users.mobile_no','users.gender','users.dob','users.qualification','users.degree_year',
            'users.course_name','users.profession','users.major_identity','users.more_about_you','users.image','users.address_line1','users.postal_code','users.country','users.state',
            'users.district','users.sub_district','users.village','cl.country_name','sl.state_name','dl.district_name','sd.sub_district_name','vl.village_name')
            ->first();
            
            if(empty($user_data)){
                $errors['user_id'] = ['User does not exists'];
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => $errors['user_id'], 'errors' =>$errors),200);
            }
            
            $user_data->image_url = (!empty($user_data->image))?url('images/user_images/'.$user_data->image):url('images/default_profile.png');
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'User Profile Data','user_data'=>$user_data),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function updateProfileData(Request $request){
        try{ 
            $data = $request->all();
            $errors = [];
            
            if($data['profile_type'] == 'general'){
                $validationRules = array('user_id'=>'required','name'=>'required','mobile_no'=>'required','gender'=>'required','dob'=>'required');
            }
            
            if($data['profile_type'] == 'qualification'){
                $validationRules = array('user_id'=>'required','qualification'=>'required');
                if(in_array($data['qualification'],['pursing_graduate','graduate','post_graduate','doctorate'])){
                    $validationRules['degree_year'] = $validationRules['course_name'] = 'required';
                }
            }
            
            if($data['profile_type'] == 'address'){
                $validationRules = array('user_id'=>'required','address_line1'=>'required','postal_code'=>'required','country'=>'required','state'=>'required','district'=>'required','sub_district'=>'required');
            }
            
            $attributes = array('mobile_no'=>'Mobile No','dob'=>'DOB','degree_year'=>'Degree Year','address_line1'=>'Address line 1');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation Errors', 'errors' => $validator->errors()),200);
            }	
            
            $user_data = User::where('id',trim($data['user_id']))->where('is_deleted',0)->where('status',1)->select('id','email','password')->first();
            
            if(empty($user_data)){
                $errors['user_id'] = ['User does not exists'];
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => $errors['user_id'], 'errors' =>$errors),200);
            }
            
            if($data['profile_type'] == 'general'){
                $updateArray = ['name'=>trim($data['name']),'mobile_no'=>trim($data['mobile_no']),'gender'=>trim($data['gender']),'dob'=>trim($data['dob']),'profession'=>trim($data['profession']),'more_about_you'=>trim($data['more_about_you'])]; 
            }
            
            if($data['profile_type'] == 'qualification'){
                $updateArray = ['qualification'=>trim($data['qualification'])]; 
                if(in_array($data['qualification'],['pursing_graduate','graduate','post_graduate','doctorate'])){
                    $updateArray['degree_year'] = trim($data['degree_year']);
                    $updateArray['course_name'] = trim($data['course_name']);
                }else{
                    $updateArray['degree_year'] = $updateArray['course_name'] = null;
                }
            }
            
            if($data['profile_type'] == 'address'){
                $village = (!empty($data['village']))?$data['village']:null;
                $updateArray = ['address_line1'=>trim($data['address_line1']),'postal_code'=>trim($data['postal_code']),'country'=>trim($data['country']),'state'=>trim($data['state']),'district'=>trim($data['district']),'sub_district'=>trim($data['sub_district']),'village'=>$village]; 
            }
            
            User::where('id',trim($data['user_id']))->update($updateArray);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Profile updated successfully'),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage().', '.$e->getLine(),'message'=>'Error in Processing Request'),200);
        }
    }
    
    function updateProfileImage(Request $request){
        try{ 
            $data = $request->all();
            
            $validationRules = array('user_id'=>'required','profile_image'=>'required');
            $attributes = array('mobile_no'=>'Mobile No','dob'=>'DOB','degree_year'=>'Degree Year','address_line1'=>'Address line 1');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation Errors', 'errors' => $validator->errors()),200);
            }	
            
            $file = $request->file('profile_image');
            $image_name = CommonHelper::uploadImage($request,$request->file('profile_image'),'images/user_images');
            
            $updateArray = ['image'=>$image_name];
            User::where('id',trim($data['user_id']))->update($updateArray);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Profile Image updated successfully'),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage().', '.$e->getLine(),'message'=>'Error in Processing Request'),200);
        }
    }    
    
    function getQualificationList(Request $request){
        try{ 
            $data = $request->all();
            
            $qualification_list = ['under_5th_class'=>'Under 5th class','under_8th_class'=>'Under 8th class','secondary'=>'Secondary (10th Class)','higher_secondary'=>'Higher Secondary (10+2)',
            'pursing_graduate'=>'Pursing Graduate','graduate'=>'Graduate','post_graduate'=>'Post Graduate','doctorate'=>'Doctorate'];
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Qualification List','qualification_list'=>$qualification_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getCountryList(Request $request){
        try{ 
            
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->where('status',1)->select('*')->orderBy('id','ASC')->get()->toArray();
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Country List','country_list'=>$country_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getStateList(Request $request){
        try{ 
            
            $data = $request->all();
            
            $content_type_header = $request->header('Content-Type');
            if(empty($content_type_header) || strtolower($content_type_header) != 'application/json' ){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Invalid Content-Type Header', 'errors' => 'Invalid Content-Type Header'),200);
            }
            
            $states_list = StateList::where('is_deleted',0)->where('status',1)->select('*');
            
            $states_list = $states_list->orderBy('id','ASC')->get()->toArray();
                
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'States List','state_list'=>$states_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getDistrictList(Request $request,$state_id){
        try{ 
            
            $data = $request->all();
            $qs_str = '';
            
            $districts_list = DistrictList::join('state_list as s', 's.id', '=', 'district_list.state_id')
            ->where('district_list.state_id',trim($state_id))        
            ->where('district_list.is_deleted',0)
            ->where('district_list.status',1)                
            ->where('s.status',1)        
            ->where('s.is_deleted',0);        
            
            /*if(isset($data['state_id']) && !empty($data['state_id'])){
                $districts_list = $districts_list->where('district_list.state_id',trim($data['state_id']));
                $qs_str.='&state_id='.trim($data['state_id']);
            }*/
            
            $districts_list = $districts_list->select('district_list.*','s.state_name')        
            ->orderBy('district_list.id','ASC')
            ->get()->toArray();        
            //->paginate($this->REC_PER_PAGE);
            
            //$districts_list = $this->updateAPIResponse($districts_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Districts List','district_list'=>$districts_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }    
    
    function getMunicipalCorporationList(Request $request){
        try{ 
            
            $data = $request->all();
            $qs_str = '';
            
            $mc1_list = Mc1List::join('state_list as s', 's.id', '=', 'mc_mahanagar_palika.state_id')
            ->where('mc_mahanagar_palika.is_deleted',0)
            ->where('mc_mahanagar_palika.status',1)             
            ->where('s.status',1)                     
            ->where('s.is_deleted',0);        
            
            if(isset($data['state_id']) && !empty($data['state_id'])){
                $mc1_list = $mc1_list->where('mc_mahanagar_palika.state_id',trim($data['state_id']));
                $qs_str.='&state_id='.$data['state_id'];
            }
            
            $mc1_list = $mc1_list->select('mc_mahanagar_palika.*','s.state_name')        
            ->orderBy('mc_mahanagar_palika.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $mc1_list = $this->updateAPIResponse($mc1_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Municipal Corporation List','mc_list'=>$mc1_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }    
    
    function getMunicipalityList(Request $request){
        try{ 
            
            $data = $request->all();
            $qs_str = '';
            
            $mc2_list = Mc2List::join('district_list as d', 'd.id', '=', 'mc_nagar_palika.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('mc_nagar_palika.is_deleted',0)
            ->where('mc_nagar_palika.status',1)
            ->where('d.status',1)
            ->where('s.status',1)        
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);
            
            if(isset($data['district_id']) && !empty($data['district_id'])){
                $mc2_list = $mc2_list->where('mc_nagar_palika.district_id',trim($data['district_id']));
                $qs_str.='&district_id='.trim($data['district_id']);
            }
            
            $mc2_list = $mc2_list->select('mc_nagar_palika.*','s.state_name','d.district_name')        
            ->orderBy('mc_nagar_palika.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $mc2_list = $this->updateAPIResponse($mc2_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Municipality List','mc_list'=>$mc2_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }    
    
    function getCityCouncilList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $city_council_list = CityCouncil::join('district_list as d', 'd.id', '=', 'city_council.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('city_council.status',1)
            ->where('s.status',1)
            ->where('d.status',1)        
            ->where('city_council.is_deleted',0)
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);  
            
            if(isset($data['district_id']) && !empty($data['district_id'])){
                $city_council_list = $city_council_list->where('city_council.district_id',trim($data['district_id']));
                $qs_str.='&district_id='.trim($data['district_id']);
            }
            
            $city_council_list = $city_council_list->select('city_council.*','s.state_name','d.district_name')        
            ->orderBy('city_council.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $city_council_list = $this->updateAPIResponse($city_council_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'City Council List','city_council_list'=>$city_council_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }    
    
    function getBlockList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $block_list = BlockList::join('district_list as d', 'd.id', '=', 'block_list.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('block_list.is_deleted',0)
            ->where('block_list.status',1)
            ->where('d.status',1)
            ->where('s.status',1)        
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);          
            
            if(isset($data['district_id']) && !empty($data['district_id'])){
                $block_list = $block_list->where('block_list.district_id',trim($data['district_id']));
                $qs_str.='&district_id='.trim($data['district_id']);
            }
            
            $block_list = $block_list->select('block_list.*','s.state_name','d.district_name')        
            ->orderBy('block_list.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $block_list = $this->updateAPIResponse($block_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Block List','block_list'=>$block_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getSubDistrictList(Request $request,$district_id){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $sub_district_list = SubDistrictList::join('district_list as d', 'd.id', '=', 'sub_district_list.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('sub_district_list.district_id',$district_id)        
            ->where('sub_district_list.is_deleted',0)
            ->where('sub_district_list.status',1)
            ->where('d.status',1)
            ->where('s.status',1)        
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);
            
            /*if(isset($data['district_id']) && !empty($data['district_id'])){
                $sub_district_list = $sub_district_list->where('sub_district_list.district_id',trim($data['district_id']));
                $qs_str.='&district_id='.trim($data['district_id']);
            }*/
            
            $sub_district_list = $sub_district_list->select('sub_district_list.*','s.state_name','d.district_name')        
            ->orderBy('sub_district_list.id','ASC')
            ->get()->toArray();           
            //->paginate($this->REC_PER_PAGE);
            
            //$sub_district_list = $this->updateAPIResponse($sub_district_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Sub District List','sub_district_list'=>$sub_district_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getVillageList(Request $request,$sub_district_id){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $village_list = VillageList::where('sub_district_id',$sub_district_id)        
            ->where('is_deleted',0)
            ->where('status',1)
            ->select('*')        
            ->orderBy('id','ASC')
            ->get()->toArray();           
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Village List','village_list'=>$village_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getPoliticalPartyList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $pp_list = PoliticalPartyList::where('political_party_list.is_deleted',0)->where('political_party_list.status',1)->where('political_party_list.status',1);
            
            $pp_list = $pp_list->select('political_party_list.*')        
            ->orderBy('political_party_list.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $pp_list = $this->updateAPIResponse($pp_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Political Party List','political_party_list'=>$pp_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getLegislativeAssemblyList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $la_constituency_list = LegislativeAssemblyConstituency::join('state_list as s', 's.id', '=', 'legislative_assembly_constituency.state_id')
            ->where('legislative_assembly_constituency.is_deleted',0)
            ->where('legislative_assembly_constituency.status',1)        
            ->where('s.is_deleted',0);
            
            if(isset($data['state_id']) && !empty($data['state_id'])){
                $la_constituency_list = $la_constituency_list->where('legislative_assembly_constituency.state_id',trim($data['state_id']));
                $qs_str.='&state_id='.trim($data['state_id']);
            }
            
            $la_constituency_list = $la_constituency_list->select('legislative_assembly_constituency.*','s.state_name')        
            ->orderBy('legislative_assembly_constituency.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $la_constituency_list = $this->updateAPIResponse($la_constituency_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Legislative Assembly List','legislative_assembly_list'=>$la_constituency_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getParliamentaryAssemblyList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $pa_constituency_list = ParliamentaryConstituency::join('state_list as s', 's.id', '=', 'parliamentary_constituency.state_id')
            ->where('parliamentary_constituency.is_deleted',0)
            ->where('parliamentary_constituency.status',1)            
            ->where('s.is_deleted',0);
            
            if(isset($data['state_id']) && !empty($data['state_id'])){
                $pa_constituency_list = $pa_constituency_list->where('parliamentary_constituency.state_id',trim($data['state_id']));
                $qs_str.='&state_id='.trim($data['state_id']);
            }
            
            $pa_constituency_list = $pa_constituency_list->select('parliamentary_constituency.*','s.state_name')        
            ->orderBy('parliamentary_constituency.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $pa_constituency_list = $this->updateAPIResponse($pa_constituency_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Parliamentary Assembly List','parliamentary_assembly_list'=>$pa_constituency_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getEROPList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $position_list = ElectedOfficialPosition::join('representation_area_list as s', 's.id', '=', 'elected_official_position.representation_area')
            ->where('elected_official_position.is_deleted',0)->where('elected_official_position.status',1);
            
            $position_list = $position_list->select('elected_official_position.*','s.representation_area as representation_area_name')        
            ->orderBy('elected_official_position.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $position_list = $this->updateAPIResponse($position_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Elected Representative Official Position List','erop_list'=>$position_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getPPOPList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $position_list = PoliticalPartyOfficialPosition::join('representation_area_list as s', 's.id', '=', 'political_party_official_position.representation_area')
            ->where('political_party_official_position.is_deleted',0)->where('political_party_official_position.status',1);
            
            $position_list = $position_list->select('political_party_official_position.*','s.representation_area as representation_area_name')        
            ->orderBy('political_party_official_position.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $position_list = $this->updateAPIResponse($position_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Political Party Official Position List','ppop_list'=>$position_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getGovernmentDepartmentList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $govt_dept_list = GovernmentDepartment::leftJoin('country_list as c', 'c.id', '=', 'government_department.country_id')
            ->leftJoin('state_list as s', 's.id', '=', 'government_department.state_id')
            ->where('government_department.status',1)        
            ->where('government_department.is_deleted',0);
            
            if(isset($data['state_id']) && !empty($data['state_id'])){
                $govt_dept_list = $govt_dept_list->where('government_department.state_id',trim($data['state_id']));
                $qs_str.='&state_id='.trim($data['state_id']);
            }
            
            $govt_dept_list = $govt_dept_list->select('government_department.*','s.state_name','c.country_name')        
            ->orderBy('government_department.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $govt_dept_list = $this->updateAPIResponse($govt_dept_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Government Department List','government_department_list'=>$govt_dept_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getNonProfitOrganizationList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $np_org_list = NonProfitOrganization::where('non_profit_organization.is_deleted',0)->where('non_profit_organization.status',1);
            
            $np_org_list = $np_org_list->select('non_profit_organization.*')        
            ->orderBy('non_profit_organization.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $np_org_list = $this->updateAPIResponse($np_org_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Non Profit Organization List','non_profit_organization_list'=>$np_org_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getGroupList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $group_list = GroupList::where('group_list.is_deleted',0)->where('group_list.status',1);
            
            $group_list  = $group_list->select('group_list.*')        
            ->orderBy('group_list.id','ASC')
            ->get()->toArray();          
            //->paginate($this->REC_PER_PAGE);
            
            $group_list = $this->updateAPIResponse($group_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Group List','group_list'=>$group_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getSubGroupList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $sub_group_list = SubGroupList::join('group_list as g', 'g.id', '=', 'sub_group_list.group_id')
            ->where('sub_group_list.is_deleted',0)        
            ->where('sub_group_list.status',1)            
            ->where('g.status',1)                    
            ->where('g.is_deleted',0);
            
            if(isset($data['group_id']) && !empty($data['group_id'])){
                $sub_group_list = $sub_group_list->where('sub_group_list.group_id',trim($data['group_id']));
                $qs_str.='&group_id='.trim($data['group_id']);
            }
            
            $sub_group_list = $sub_group_list->select('sub_group_list.*','g.group_name')        
            ->orderBy('sub_group_list.id','ASC')
            ->get()->toArray();        
            //->paginate($this->REC_PER_PAGE);
            
            $sub_group_list = $this->updateAPIResponse($sub_group_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Sub Group List','sub_group_list'=>$sub_group_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getSubmissionPurposeList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $sub_purpose_list = SubmissionPurposeList::join('group_list as g', 'g.id', '=', 'submission_purpose_list.group_id')
            ->where('submission_purpose_list.is_deleted',0)    
            ->where('submission_purpose_list.status',1)                
            ->where('g.status',1)            
            ->where('g.is_deleted',0);
            
            if(isset($data['group_id']) && !empty($data['group_id'])){
                $sub_purpose_list = $sub_purpose_list->where('submission_purpose_list.group_id',trim($data['group_id']));
                $qs_str.='&group_id='.trim($data['group_id']);
            }
            
            $sub_purpose_list = $sub_purpose_list->select('submission_purpose_list.*','g.group_name')        
            ->orderBy('submission_purpose_list.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $sub_purpose_list = $this->updateAPIResponse($sub_purpose_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Purpose List','submission_purpose_list'=>$sub_purpose_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getSubmissionTypeList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $sub_type_list = SubmissionTypeList::join('group_list as g', 'g.id', '=', 'submission_type_list.group_id')
            ->where('submission_type_list.is_deleted',0)        
            ->where('submission_type_list.status',1)                
            ->where('g.status',1)                        
            ->where('g.is_deleted',0);
            
            if(isset($data['group_id']) && !empty($data['group_id'])){
                $sub_type_list = $sub_type_list->where('submission_type_list.group_id',trim($data['group_id']));
                $qs_str.='&group_id='.trim($data['group_id']);
            }
            
            $sub_type_list = $sub_type_list->select('submission_type_list.*','g.group_name')        
            ->orderBy('submission_type_list.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $sub_type_list = $this->updateAPIResponse($sub_type_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Type List','submission_type_list'=>$sub_type_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getReviewLevelList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $review_level_list = ReviewLevel::where('review_level.is_deleted',0)->where('review_level.status',1);
            
            $review_level_list = $review_level_list->select('review_level.*')        
            ->orderBy('review_level.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $review_level_list = $this->updateAPIResponse($review_level_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Review Level List','review_level_list'=>$review_level_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getSubmissionSubscribersList(Request $request,$sub_group_id,$user_id){
        try{ 
            $data = $request->all();
            
            $user_data = User::where('id',$user_id)->where('is_deleted',0)->first();
            $sub_group_data = SubGroupList::where('id',$sub_group_id)->where('is_deleted',0)->first();
            
            if(!empty($user_data)){
                if(!empty($user_data->country) && !empty($user_data->state) && !empty($user_data->district)){
                    $user_country = $user_data->country;
                    $user_state = $user_data->state;
                    $user_district = $user_data->district;
                }else{
                    return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => 'Country/State/District is empty'),200);
                }
            }else{
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => 'User does not exists'),200);
            }
            
            if(empty($sub_group_data)){
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => 'SubGroup does not exists'),200);
            }
            
            $subscriber_list = SubscriberList::join('subscriber_package as sp', 'sp.subscriber_id', '=', 'subscriber_list.id')
            ->join('users as u', 'u.id', '=', 'subscriber_list.user_id')        
            ->whereRaw("((sp.submission_range = 'country' AND  sp.country = $user_country) OR (sp.submission_range = 'state' AND FIND_IN_SET($user_state,sp.state)) OR (sp.submission_range = 'district' AND FIND_IN_SET($user_district,sp.district)))")        
            ->where('subscriber_list.office_belongs_to',$sub_group_id)
            ->where('sp.is_deleted',0)
            ->where('subscriber_list.is_deleted',0)       
            ->where('u.is_deleted',0)        
            ->where('sp.status',1)
            ->where('subscriber_list.status',1)
            ->select('subscriber_list.*','u.name as subscriber_name','u.email')        
            ->orderBy('subscriber_list.id','ASC')
            ->get()->toArray();
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Subscribers List','subscriber_list'=>$subscriber_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getSubmissionSubscriberData(Request $request,$subscriber_id){
        try{ 
            $data = $request->all();
            
            $subscriber_data = SubscriberList::join('users as u', 'u.id', '=', 'subscriber_list.user_id')        
            ->where('subscriber_list.id',$subscriber_id)
            ->where('subscriber_list.is_deleted',0)
            ->where('u.is_deleted',0)        
            ->select('subscriber_list.*','u.name as subscriber_name')                 
            ->first();
            
            $sub_group_data = SubGroupList::where('id',$subscriber_data->office_belongs_to)->where('is_deleted',0)->first();
            
            $submission_purpose_list = SubmissionPurposeList::where('group_id',$sub_group_data->group_id)->where('is_deleted',0)->where('status',1)->get()->toArray();
            $submission_type_list = SubmissionTypeList::where('group_id',$sub_group_data->group_id)->where('is_deleted',0)->where('status',1)->get()->toArray();
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Subscriber Data','subscriber_data'=>$subscriber_data,'submission_purpose_list'=>$submission_purpose_list,'submission_type_list'=>$submission_type_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function saveSubmissionDetail(Request $request){
        try{ 
            $data = $request->all();
            
            $file = $request->file('detail_file');
            $file_name = CommonHelper::uploadImage($request,$request->file('detail_file'),'documents/submission_documents',false);
            
            $subscriber_data = SubscriberList::where('id',trim($data['subscriber_id']))->first();
            
            $insertArray = ['sub_group_id'=>$subscriber_data->office_belongs_to,'place'=>null,'subscriber_id'=>trim($data['subscriber_id']),'submission_type'=>trim($data['submission_type_id']),
            'purpose'=>trim($data['purpose']),'nature'=>trim($data['nature']),'subject'=>trim($data['subject']),'summary'=>trim($data['summary']),'file'=>$file_name,
            'user_id'=>trim($data['user_id'])];
            
            $submission = Submissions::create($insertArray);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission added successfully','submission_data'=>$submission),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getSubmissionData(Request $request,$submission_id){
        try{ 
            $data = $request->all();
            
            $submission_data = Submissions::join('subscriber_list as sl', 'sl.id', '=', 'submissions.subscriber_id')
            ->join('users as u1', 'u1.id', '=', 'sl.user_id')                
            ->join('users as u2', 'u2.id', '=', 'submissions.user_id')        
            ->join('submission_type_list as st', 'st.id', '=', 'submissions.submission_type')        
            ->join('submission_purpose_list as sp', 'sp.id', '=', 'submissions.purpose')
            ->leftJoin('country_list as c', 'c.id', '=', 'u2.country')                     
            ->leftJoin('state_list as s', 's.id', '=', 'u2.state')                             
            ->leftJoin('district_list as dl', 'dl.id', '=', 'u2.district')               
            ->leftJoin('sub_district_list as sd', 'sd.id', '=', 'u2.sub_district')          
            ->where('submissions.id',$submission_id)                
            ->where('submissions.is_deleted',0)        
            ->where('sl.is_deleted',0)                
            ->where('u1.is_deleted',0)          
            ->where('u2.is_deleted',0)     
            ->where('st.is_deleted',0)          
            ->where('sp.is_deleted',0)                  
            ->select('submissions.*','u1.name as subscriber_name','sl.bio as subscriber_bio','u2.name as submitted_by_name','u2.email as submitted_by_email','u2.mobile_no as submitted_by_mobile_no',
            'u2.gender as submitted_by_gender','u2.dob as submitted_by_dob','u2.qualification as submitted_by_qualification','u2.profession as submitted_by_profession',
            'u2.address_line1 as submitted_by_address_line1','u2.postal_code as submitted_by_postal_code','c.country_name as submitted_by_country_name','u2.image as submitted_by_profile_image',
            's.state_name as submitted_by_state_name','dl.district_name as submitted_by_district_name','sd.sub_district_name as submitted_by_sub_district_name','sp.submission_purpose','st.submission_type')        
            ->first();        
            
            if(!empty($submission_data)){
                $submission_data->submitted_by_profile_image_url = (!empty($submission_data->submitted_by_profile_image))?url('images/user_images/'.$submission_data->submitted_by_profile_image):url('images/default_profile.png');
                $submission_data->file_url = (!empty($submission_data->file))?url('documents/submission_documents/'.$submission_data->file):'';
            }
            
            //print_r($submission_data);
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Data','submission_data'=>$submission_data),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function saveSubmissionConfirm(Request $request){
        try{ 
            $data = $request->all();
            
            $submission_id = trim($data['submission_id']);
            
            $updateArray = ['submission_status'=>'completed'];
            
            $submission = Submissions::where('id',$submission_id)->update($updateArray);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission updated successfully'),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getUserTeamsList(Request $request,$user_id){
        try{ 
            $data = $request->all();
            
            $users_teams = TeamMembers::join('subscriber_list as sl', 'sl.id', '=', 'team_members.subscriber_id')
            ->join('users as u1', 'u1.id', '=', 'sl.user_id')      
            ->where('team_members.user_id',$user_id)                
            ->where('team_members.is_deleted',0)        
            ->where('sl.is_deleted',0)                
            ->where('u1.is_deleted',0)        
            ->where('team_members.status',1)        
            ->where('sl.status',1)                
            ->where('u1.status',1)     
            ->select('team_members.user_id','team_members.subscriber_id','u1.name as subscriber_name','sl.bio as subscriber_bio')        
            ->get()->toArray();                
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'User Teams','users_teams'=>$users_teams),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getTeamMembersList(Request $request,$subscriber_id){
        try{ 
            $data = $request->all();
            
            $team_members = TeamMembers::join('subscriber_list as sl', 'sl.id', '=', 'team_members.subscriber_id')
            ->join('team_designations as td', 'td.id', '=', 'team_members.designation_id')           
            ->join('representation_area_list as ral', 'ral.id', '=', 'td.rep_area_id')         
            ->join('users as u1', 'u1.id', '=', 'sl.user_id')      
            ->join('users as u2', 'u2.id', '=', 'team_members.user_id')          
            ->where('team_members.subscriber_id',$subscriber_id)                
            ->where('team_members.is_deleted',0)        
            ->where('sl.is_deleted',0)                
            ->where('u1.is_deleted',0)        
            ->where('u2.is_deleted',0)                
            ->where('team_members.status',1)        
            ->where('sl.status',1)                
            ->where('u1.status',1)     
            ->where('u2.status',1)        
            ->select('team_members.subscriber_id','team_members.designation_id','u1.name as subscriber_name','sl.bio as subscriber_bio','u2.id as team_member_id','u2.name as team_member_name',
            'u2.email as team_member_email','u2.mobile_no as team_member_mobile_no','td.designation_name','ral.representation_area')        
            ->get()->toArray();        
            
            $subscriber_data = SubscriberList::join('users as u', 'u.id', '=', 'subscriber_list.user_id')        
            ->where('subscriber_list.id',$subscriber_id)
            ->where('subscriber_list.is_deleted',0)
            ->where('u.is_deleted',0)        
            ->select('subscriber_list.*','u.name as subscriber_name')                 
            ->first();
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Team Members List','team_members'=>$team_members,'subscriber_data'=>$subscriber_data),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function getSubscribersList(Request $request){
        try{ 
            $data = $request->all();
            
            $subscribers_list = SubscriberList::join('users as u', 'u.id', '=', 'subscriber_list.user_id')      
            ->join('district_list as d', 'd.id', '=', 'u.district')          
            ->join('state_list as s', 's.id', '=', 'u.state')           
            ->join('sub_group_list as sg', 'sg.id', '=', 'subscriber_list.office_belongs_to')          
            ->join('group_list as g', 'g.id', '=', 'sg.group_id');           
            
            if(isset($data['name']) && !empty($data['name'])){
                $subscribers_list = $subscribers_list->where('u.name','LIKE','%'.trim($data['name'].'%'));
            }
            
            if(isset($data['district']) && !empty($data['district'])){
                $subscribers_list = $subscribers_list->where('d.district_name','LIKE','%'.trim($data['district'].'%'));
            }
            
            $subscribers_list = $subscribers_list->where('subscriber_list.is_deleted',0)        
            ->where('u.is_deleted',0)                
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0)                
            ->where('sg.is_deleted',0)        
            ->where('g.is_deleted',0)                
            ->where('subscriber_list.status',1)        
            ->where('u.status',1)                
            ->where('d.status',1)        
            ->where('s.status',1)                
            ->where('sg.status',1)        
            ->where('g.status',1)               
            ->select('subscriber_list.id as subscriber_id','subscriber_list.bio as subscriber_bio','subscriber_list.office_belongs_to','u.name as subscriber_name','u.email',
            'd.district_name','s.state_name','sg.sub_group_name','sg.id as sub_group_id','g.id as group_id','g.group_name','g.group_sub_type')        
            ->get()->toArray();        
            
            if(isset($data['user_id']) && !empty($data['user_id'])){
                $following = SubscriberFollowers::where('user_id',trim($data['user_id']))->where('is_deleted',0)->select('subscriber_id')->get()->toArray();
                $subscriber_ids = array_column($following,'subscriber_id');
                for($i=0;$i<count($subscribers_list);$i++){
                    $subscribers_list[$i]['following'] = (in_array($subscribers_list[$i]['subscriber_id'], $subscriber_ids))?1:0;
                }
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscribers List','subscribers_list'=>$subscribers_list),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function addSubscriberFollower(Request $request){
        try{ 
            $data = $request->all();
            $validationRules = array('user_id'=>'required','subscriber_id'=>'required');
            $attributes = array('user_id'=>'User','subscriber_id'=>'Subscriber');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Missing Required Fields', 'errors' => $validator->errors()),200);
            }	
            
            $follow = SubscriberFollowers::where('subscriber_id',trim($data['subscriber_id']))->where('user_id',trim($data['user_id']))->first();
            
            if(empty($follow)){
                $insertArray = ['subscriber_id'=>trim($data['subscriber_id']),'user_id'=>trim($data['user_id'])];
                $follow = SubscriberFollowers::create($insertArray);
            }else{
                $updateArray = ['is_deleted'=>0];
                SubscriberFollowers::where('id',$follow->id)->update($updateArray);
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscriber Follower added successfully','follow'=>$follow),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function deleteSubscriberFollower(Request $request){
        try{ 
            $data = $request->all();
            $validationRules = array('user_id'=>'required','subscriber_id'=>'required');
            $attributes = array('user_id'=>'User','subscriber_id'=>'Subscriber');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Missing Required Fields', 'errors' => $validator->errors()),200);
            }	
            
            $updateArray = ['is_deleted'=>1];
            SubscriberFollowers::where('subscriber_id',trim($data['subscriber_id']))->where('user_id',trim($data['user_id']))->update($updateArray);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscriber Follower updated successfully'),200);
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function updateAPIResponse($records_list,$qs_str){
        
        $records_list = json_decode(json_encode($records_list),true);
        
        if(!empty($records_list['data'])){
            $records_list['next_page_url'] = (!empty($records_list['next_page_url']))?$records_list['next_page_url'].$qs_str:$records_list['next_page_url'];
            $records_list['prev_page_url'] = (!empty($records_list['prev_page_url']))?$records_list['prev_page_url'].$qs_str:$records_list['prev_page_url'];
            $records_list['first_page_url'] = (!empty($records_list['first_page_url']))?$records_list['first_page_url'].$qs_str:$records_list['first_page_url'];
            $records_list['last_page_url'] = (!empty($records_list['last_page_url']))?$records_list['last_page_url'].$qs_str:$records_list['last_page_url'];
        }
        
        unset($records_list['links']);
        return $records_list;
    } 
}
