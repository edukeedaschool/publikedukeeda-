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
        $this->middleware('auth');
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
            'country'=>'required','state'=>'required','district'=>'required','subDistrict'=>'required','village'=>'required','userStatus'=>'required');
            
            $attributes = array('userName'=>'Name','emailAddress'=>'Email Address','mobileNumber'=>'Mobile Number','majorIdentity'=>'Major Identity','moreAboutYou'=>'More About You',
            'userImage'=>'User Image','addressLine1'=>'Address Line 1','postalCode'=>'Postal Code','userStatus'=>'Status','DOB'=>'DOB','subDistrict'=>'Sub District','passOutYear'=>'Degree Year');
            
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
            'sub_district'=>trim($data['subDistrict']),'village'=>trim($data['village']),'status'=>trim($data['userStatus']),'password'=>Hash::make('12345678'),
            'more_about_you'=>trim($data['moreAboutYou']),'user_role'=>1);
       
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
            ->where('users.is_deleted',0)        
            ->select('users.*','ur.role_name')        
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
    
}
