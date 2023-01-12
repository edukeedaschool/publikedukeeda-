<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StateList;
use App\Models\CountryList;
use App\Models\DistrictList;
use App\Models\VillageList;
use App\Models\User;
use App\Helpers\CommonHelper;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    public function login(Request $request){
        try{
            $data = $request->all();
            
            if(isset(Auth::user()->id)){
                return redirect('/');
            }
            
            $params = ['title'=>'User Login'];
            
            return view('front/user/login',$params);
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitLogin(Request $request){
        try{
            $data = $request->all();
            
            $url = url('/api/login');
            $postData = json_encode(['email'=>trim($data['email']),'password'=>trim($data['password'])]);
            $headers = CommonHelper::getAPIHeaders();//print_r($headers);exit;
            
            $response = CommonHelper::processCURLRequest($url,$postData,'','',$headers);
            $response = json_decode($response,true);
            
            if(isset($response['user_data']['id'])){
                Auth::loginUsingId($response['user_data']['id']);
                return redirect('/');
            }else{
                return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            }
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function signup(Request $request){
        try{
            $data = $request->all();
            
            if(isset(Auth::user()->id)){
                return redirect('/');
            }
            
            $params = ['title'=>'User Signup'];
            
            return view('front/user/signup',$params);
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitSignup(Request $request){
        try{
            $data = $request->all();
            
            $url = url('/api/signup');
            $postData = json_encode(['name'=>trim($data['name']),'user_name'=>trim($data['user_name']),'email'=>trim($data['email']),'password'=>trim($data['password']),'password_confirmation'=>trim($data['password_confirmation'])]);
            $headers = CommonHelper::getAPIHeaders();
            
            $response = CommonHelper::processCURLRequest($url,$postData,'','',$headers);
            $response = json_decode($response,true);//print_r($response);exit;
            
            if(isset($response['user_data']['id'])){
                
                return redirect('user/login')->with('message', 'Signup completed successfully. Login here');;
                //return redirect()->back()->with('message', $response['message']);
            }else{
                return back()->withErrors($response['errors'])->withInput($data);
            }
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function changePassword(Request $request){
        try{
            $data = $request->all();
            
           $params = ['title'=>'Change Password'];
            
            return view('front/user/change_password',$params);
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitChangePassword(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $url = url('/api/change-password');
            $postData = json_encode(['email'=>$user->email,'current_password'=>trim($data['current_password']),'new_password'=>trim($data['new_password']),'new_password_confirmation'=>trim($data['new_password_confirmation'])]);
            $headers = CommonHelper::getAPIHeaders();//print_r($headers);exit;
            
            $response = CommonHelper::processCURLRequest($url,$postData,'','',$headers);
            $response = json_decode($response,true);
            
            if(isset($response['status']) && $response['status'] == 'success'){
                return redirect()->back()->with('message', $response['message']);
            }else{
                return back()->withErrors($response['errors'])->withInput($data);
            }
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage().$e->getLine()));
        }
    }
    
    public function profile(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            
            $url = url('/api/country/list');
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);
            $response = json_decode($response,true);
            $country_list = $response['country_list'];
            
            $url = url('/api/state/list');
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);
            $response = json_decode($response,true);
            $states_list = $response['state_list'];
            
            $url = url('/api/profile/data/'.$user->id);
            $postData = [];
            $response = CommonHelper::processCURLRequest($url,$postData,'','',$headers);//print_r($response);
            $response = json_decode($response,true);
            $user_profile = $response['user_data'];
            
            $url = url('/api/qualification/list');
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);
            $response = json_decode($response,true);
            $qualification_list = $response['qualification_list'];
            
            $params = ['title'=>'User Profile','country_list'=>$country_list,'states_list'=>$states_list,'qualification_list'=>$qualification_list,'user_profile'=>$user_profile];
            
            return view('front/user/profile',$params);
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage().', '.$e->getLine()));
        }
    }
    
    public function viewProfile(Request $request,$user_id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            
            $url = url('/api/profile/data/'.$user_id.'?user_id='.$user->id);
            $postData = [];
            $response = CommonHelper::processCURLRequest($url,$postData,'','',$headers);//print_r($response);
            $response = json_decode($response,true);
            $user_profile = $response['user_data'];
            
            $params = ['title'=>'View User Profile','user_profile'=>$user_profile,'user'=>$user];
            
            return view('front/user/profile_view',$params);
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage().', '.$e->getLine()));
        }
    }
    
    function getAPIData(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            $response = [];
            
            $headers = CommonHelper::getAPIHeaders();
            
            if(isset($data['action']) && $data['action'] == 'district_list'){
                $url = url('/api/district/list/'.$data['state_id']);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);
                $response = json_decode($response,true);
            }
            
            if(isset($data['action']) && $data['action'] == 'sub_district_list'){
                $url = url('/api/sub-district/list/'.$data['district_id']);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);
                $response = json_decode($response,true);
            }
            
            if(isset($data['action']) && $data['action'] == 'village_list'){
                $url = url('/api/village/list/'.$data['sub_district_id']);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);
                $response = json_decode($response,true);
            }
            
            if(isset($data['action']) && $data['action'] == 'update_profile'){
                $url = url('/api/profile/update');
                $response = CommonHelper::processCURLRequest($url,json_encode($data),'','',$headers);
                $response = json_decode($response,true);
            }
            
            if(isset($data['action']) && $data['action'] == 'update_profile_image'){
                $validationRules = array('profile_image'=>'required');
                $attributes = array();

                $validator = Validator::make($data,$validationRules,array(),$attributes);
                if ($validator->fails()){ 
                    return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation Errors', 'errors' => $validator->errors()),200);
                }	
                
                $file = $request->file('profile_image');
                $filePath=  ($file->getRealPath());
                $fileMime = $file->getMimeType(); 
                $fileName = $file->getClientOriginalName(); 
                $cfile = new \CURLFile( $filePath,$fileMime,$fileName);
                
                $postFileDataArray['profile_image'] = $cfile;
                $postFileDataArray['user_id'] = $user->id;
                
                $method 		= 'POST'; 
                $url                    = url('/api/profile/update-image');
                $authAPIToken 		= '';
                
                $curl = curl_init();	
                curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($curl, CURLOPT_URL, $url);
                //curl_setopt($curl, CURLOPT_HEADER, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer $authAPIToken",'Content-Type: multipart/form-data','Accept: application/json'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS,$postFileDataArray);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                
                $response = curl_exec($curl);
                curl_close($curl);
            }
            
            return $response; 
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }
    }
    
    public function addUser(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            $params = ['country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add User'];
            
            return view('admin/user/user_add',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddUser(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('userName'=>'required','emailAddress'=>'required|email','mobileNumber'=>'required','gender'=>'required','DOB'=>'required','qualification'=>'required',
            'profession'=>'required','majorIdentity'=>'required','moreAboutYou'=>'required','userImage'=>'required|image|mimes:jpeg,png,jpg,gif|max:3072','addressLine1'=>'required','postalCode'=>'required',
            'country'=>'required','state'=>'required','district'=>'required','subDistrict'=>'required','village'=>'required','userStatus'=>'required','password'=>'required|min:6|max:100|confirmed','user_Name'=>'required');
            
            $attributes = array('userName'=>'Name','emailAddress'=>'Email Address','mobileNumber'=>'Mobile Number','majorIdentity'=>'Major Identity','moreAboutYou'=>'More About You',
            'userImage'=>'User Image','addressLine1'=>'Address Line 1','postalCode'=>'Postal Code','userStatus'=>'Status','DOB'=>'DOB','subDistrict'=>'Sub District','passOutYear'=>'Degree Year','user_Name'=>'Username');
            
            if(in_array($data['qualification'],['graduate','post_graduate','doctorate','pursing_graduate']) ){
                $validationRules['passOutYear'] = 'required';
                $validationRules['courseName'] = 'required';
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $user_exists = User::where('email',trim($data['emailAddress']))->where('is_deleted',0)->first();
            if(!empty($user_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User already exists with Email Address', 'errors' => 'User already exists with Email Address'));
            }
            
            $user_exists = User::where('user_name',trim($data['user_Name']))->where('is_deleted',0)->first();
            if(!empty($user_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User already exists with Username', 'errors' => 'User already exists with Username'));
            }
            
            if(in_array($data['qualification'],['graduate','post_graduate','doctorate','pursing_graduate']) ){
                $degree_year = $data['passOutYear'];
                $course_name = $data['courseName'];
            }else{
                $degree_year = $course_name = null;
            }
            
            $image_name = CommonHelper::uploadImage($request,$request->file('userImage'),'images/user_images');
            
            $insertArray = array('name'=>trim($data['userName']),'email'=>trim($data['emailAddress']),'mobile_no'=>trim($data['mobileNumber']),'gender'=>trim($data['gender']),
            'dob'=>trim($data['DOB']),'qualification'=>trim($data['qualification']),'degree_year'=>$degree_year,'course_name'=>$course_name,
            'profession'=>trim($data['profession']),'major_identity'=>trim($data['majorIdentity']),'image'=>$image_name,'address_line1'=>trim($data['addressLine1']),
            'postal_code'=>trim($data['postalCode']),'country'=>trim($data['country']),'state'=>trim($data['state']),'district'=>trim($data['district']),
            'sub_district'=>trim($data['subDistrict']),'village'=>trim($data['village']),'status'=>trim($data['userStatus']),'password'=>Hash::make(trim($data['password'])),
            'more_about_you'=>trim($data['moreAboutYou']),'user_role'=>3,'user_name'=>trim($data['user_Name']));
       
            $user = User::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'User added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editUser(Request $request,$id){
        try{
            $data = $request->all();
            $user_id = $id;
            
            $user_data = User::where('id',$user_id)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            $params = ['country_list'=>$country_list,'states_list'=>$states_list,'user_data'=>$user_data,'title'=>'Edit User'];
            
            return view('admin/user/user_edit',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditUser(Request $request,$id){
        try{
            $data = $request->all();
           
            $user_id = $id;
            $user_data = User::where('id',$user_id)->first();
            
            $validationRules = array('userName'=>'required','emailAddress'=>'required|email','mobileNumber'=>'required','gender'=>'required','DOB'=>'required','qualification'=>'required',
            'profession'=>'required','majorIdentity'=>'required','moreAboutYou'=>'required','userImage'=>'image|mimes:jpeg,png,jpg,gif|max:3072','addressLine1'=>'required','postalCode'=>'required',
            'country'=>'required','state'=>'required','district'=>'required','subDistrict'=>'required','village'=>'required','userStatus'=>'required');
            
            $attributes = array('userName'=>'Name','emailAddress'=>'Email Address','mobileNumber'=>'Mobile Number','majorIdentity'=>'Major Identity','moreAboutYou'=>'More About You',
            'userImage'=>'User Image','addressLine1'=>'Address Line 1','postalCode'=>'Postal Code','userStatus'=>'Status','DOB'=>'DOB','subDistrict'=>'Sub District','passOutYear'=>'Degree Year');
            
            if(in_array($data['qualification'],['graduate','post_graduate','doctorate','pursing_graduate']) ){
                $validationRules['passOutYear'] = 'required';
                $validationRules['courseName'] = 'required';
            }
            
            if(isset($data['update_password']) && $data['update_password'] == 1){
                $validationRules['password'] = 'required|min:6|max:100|confirmed';
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $user_exists = User::where('email',trim($data['emailAddress']))->where('id','!=',$user_id)->where('is_deleted',0)->first();
            if(!empty($user_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User already exists with Email Address', 'errors' => 'User already exists with Email Address'));
            }
            
            if(in_array($data['qualification'],['graduate','post_graduate','doctorate','pursing_graduate']) ){
                $degree_year = $data['passOutYear'];
                $course_name = $data['courseName'];
            }else{
                $degree_year = $course_name = null;
            }
            
            $updateArray = array('name'=>trim($data['userName']),'email'=>trim($data['emailAddress']),'mobile_no'=>trim($data['mobileNumber']),'gender'=>trim($data['gender']),
            'dob'=>trim($data['DOB']),'qualification'=>trim($data['qualification']),'degree_year'=>$degree_year,'course_name'=>$course_name,
            'profession'=>trim($data['profession']),'major_identity'=>trim($data['majorIdentity']),'address_line1'=>trim($data['addressLine1']),
            'postal_code'=>trim($data['postalCode']),'country'=>trim($data['country']),'state'=>trim($data['state']),'district'=>trim($data['district']),
            'sub_district'=>trim($data['subDistrict']),'village'=>trim($data['village']),'status'=>trim($data['userStatus']),'more_about_you'=>trim($data['moreAboutYou']));
            
            if(isset($data['update_password']) && $data['update_password'] == 1){
                $updateArray['password'] = Hash::make(trim($data['password']));
            }
            
            if(!empty($request->file('userImage'))){
                $image_name = CommonHelper::uploadImage($request,$request->file('userImage'),'images/user_images');
                $updateArray['image'] = $image_name;
            }
       
            $user = User::where('id',$user_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'User updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listUser(Request $request){
        try{
            $data = $request->all();
            
            $user_list = User::join('user_roles as ur', 'ur.id', '=', 'users.user_role')
            ->where('users.is_deleted',0);
            
            if(isset($data['user_name']) && !empty($data['user_name'])){
                $user_name = trim($data['user_name']);
                $user_list = $user_list->whereRaw("(users.name LIKE '%$user_name%' OR users.email LIKE '%$user_name%')");
            }
            
            $user_list = $user_list->select('users.*','ur.role_name')        
            ->orderBy('users.id','ASC')
            ->paginate(50);
            
            return view('admin/user/user_list',array('title'=>'User List','user_list'=>$user_list));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function updateUser(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $ids = explode(',',trim($data['ids']));
            if(empty($data['ids'])){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Please select Users', 'errors' => 'Please select Users'));
            }
            
            if($data['action'] == 'delete'){
                User::wherein('id',$ids)->update(['is_deleted'=>1]);
            }
            
            if($data['action'] == 'disable'){
                User::wherein('id',$ids)->update(['status'=>0]);
            }
            
            if($data['action'] == 'enable'){
                User::wherein('id',$ids)->update(['status'=>1]);
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Users updated successfully'),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getUserData(Request $request,$email){
        try{
            $data = $request->all();
            $user_data = User::where('email',$email)->first();   
           
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'User Data','user_data'=>$user_data),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function listUserFollowers(Request $request,$user_id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $query_string = 'user_id='.$user->id.'&'.$_SERVER['QUERY_STRING'];
            $url = url('/api/user/followers/'.$user_id.'?'.$query_string);
            
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);
            $response = json_decode($response,true);
            
            $user_followers = isset($response['user_followers'])?$response['user_followers']:[];
            $user_following_users = isset($response['user_following_users'])?$response['user_following_users']:[];
            $user_following_subscribers = isset($response['user_following_subscribers'])?$response['user_following_subscribers']:[];
            
            $params = ['title'=>'View User Followers','user_followers'=>$user_followers,'user_following_users'=>$user_following_users,'user_following_subscribers'=>$user_following_subscribers];
            
            return view('front/user/user_followers_list',$params);
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage().', '.$e->getLine()));
        }
    }
    
    function addUserFollower(Request $request){
        try{ 
            $data = $request->all();
            $user = Auth::user();
            $validationRules = array('user_id'=>'required');
            $attributes = array('user_id'=>'User');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Missing Required Fields', 'errors' => $validator->errors()),200);
            }	
            
            $post_data = $data; 
            $post_data['follower_id'] = $user->id;
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/user/follow');
            $response = CommonHelper::processCURLRequest($url,json_encode($post_data),'','',$headers);
            $response = json_decode($response,true);
            
            return $response;
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function deleteUserFollower(Request $request){
        try{ 
            $data = $request->all();
            $user = Auth::user();
            $validationRules = array('user_id'=>'required');
            $attributes = array('user_id'=>'User');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Missing Required Fields', 'errors' => $validator->errors()),200);
            }	
            
            $post_data = $data; 
            $post_data['follower_id'] = $user->id;
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/user/unfollow');
            $response = CommonHelper::processCURLRequest($url,json_encode($post_data),'','',$headers);
            $response = json_decode($response,true);
            
            return $response;
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function createUserMessage(Request $request,$to_user_id){
        try{
            $data = $request->all();
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/profile/data/'.$to_user_id);
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);
            $response = json_decode($response,true);
            
            $to_user_data = isset($response['user_data'])?$response['user_data']:[];
            
            return view('front/user/user_message_create',array('title'=>'Create Message','to_user_data'=>$to_user_data));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    function saveUserMessage(Request $request){
        try{ 
            $data = $request->all();
            $user = Auth::user();
            
            $post_data = $data; 
            $post_data['from_id'] = $user->id;//print_r($post_data);exit;
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/user/message/add');
            $response = CommonHelper::processCURLRequest($url,json_encode($post_data),'','',$headers);
            $response = json_decode($response,true);
            
            return $response;
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function listUserMessages(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/user/message/list/'.$user->id);
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);
            $response = json_decode($response,true);
            
            $message_list = isset($response['message_list'])?$response['message_list']:[];
            
            return view('front/user/user_message_list',array('title'=>'User Messages','message_list'=>$message_list));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
}
