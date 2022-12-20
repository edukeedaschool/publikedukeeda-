<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StateList;
use App\Models\CountryList;
use App\Models\DistrictList;
use App\Models\VillageList;
use App\Models\TeamDesignations;
use App\Models\TeamMembers;
use App\Models\RepresentationAreaList;
use App\Models\User;
use App\Helpers\CommonHelper;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
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

    public function addTeamDesignation(Request $request){
        try{
            $data = $request->all();
            $rep_area_list = RepresentationAreaList::where('is_deleted',0)->get()->toArray();
            
            $params = ['rep_area_list'=>$rep_area_list,'title'=>'Add Team Designation'];
            
            return view('admin/team/team_designation_add',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddTeamDesignation(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('designationName'=>'required','representationArea'=>'required','designationStatus'=>'required');
            
            $attributes = array('designationName'=>'Designation Name','representationArea'=>'Representation Area','designationStatus'=>'Designation Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $designation_exists = TeamDesignations::where('designation_name',trim($data['designationName']))->where('rep_area_id',trim($data['representationArea']))->where('subscriber_id',$user->id)->where('is_deleted',0)->first();
            if(!empty($designation_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Designation already exists', 'errors' => 'Designation already exists'));
            }
            
            $insertArray = array('designation_name'=>trim($data['designationName']),'rep_area_id'=>trim($data['representationArea']),'subscriber_id'=>$user->id);
       
            $designation = TeamDesignations::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Designation added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editTeamDesignation(Request $request,$id){
        try{
            $data = $request->all();
            $designation_id = $id;
            
            $designation_data = TeamDesignations::where('id',$designation_id)->first();
            $rep_area_list = RepresentationAreaList::where('is_deleted',0)->get()->toArray();
            
            $params = ['rep_area_list'=>$rep_area_list,'designation_data'=>$designation_data,'title'=>'Edit Team Designation'];
            
            return view('admin/team/team_designation_edit',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditTeamDesignation(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $designation_id = $id;
            $designation_data = TeamDesignations::where('id',$designation_id)->first();
            
            $validationRules = array('designationName'=>'required','representationArea'=>'required','designationStatus'=>'required');
            
            $attributes = array('designationName'=>'Designation Name','representationArea'=>'Representation Area','designationStatus'=>'Designation Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $designation_exists = TeamDesignations::where('designation_name',trim($data['designationName']))->where('rep_area_id',trim($data['representationArea']))->where('subscriber_id',$user->id)->where('id','!=',$designation_id)->where('is_deleted',0)->first();
            if(!empty($designation_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Designation already exists', 'errors' => 'Designation already exists'));
            }
            
            $updateArray = array('designation_name'=>trim($data['designationName']),'rep_area_id'=>trim($data['representationArea']),'status'=>$data['designationStatus']);
            
            TeamDesignations::where('id',$designation_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Designation updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listTeamDesignation(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $designation_list = TeamDesignations::join('representation_area_list as ral', 'ral.id', '=', 'team_designations.rep_area_id')
            ->join('users as u', 'u.id', '=', 'team_designations.subscriber_id')        
            ->where('team_designations.subscriber_id',$user->id)               
            ->where('team_designations.is_deleted',0)        
            ->where('ral.is_deleted',0)                
            ->where('u.is_deleted',0)                
            ->select('team_designations.*','ral.representation_area','u.name as subscriber_name')        
            ->orderBy('team_designations.id','ASC')
            ->paginate(50);
            
            return view('admin/team/team_designation_list',array('title'=>'Team Designation List','designation_list'=>$designation_list));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function updateTeamDesignation(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $ids = explode(',',trim($data['ids']));
            
            if(empty($data['ids'])){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Please select Designation', 'errors' => 'Please select Designation'));
            }
            
            if($data['action'] == 'delete'){
                TeamDesignations::wherein('id',$ids)->update(['is_deleted'=>1]);
            }
            
            if($data['action'] == 'disable'){
                TeamDesignations::wherein('id',$ids)->update(['status'=>0]);
            }
            
            if($data['action'] == 'enable'){
                TeamDesignations::wherein('id',$ids)->update(['status'=>1]);
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Designations updated successfully'),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listTeamMember(Request $request){
        try{
            $data = $request->all();
            
            $member_list = TeamMembers::join('team_designations as td', 'td.id', '=', 'team_members.designation_id')
            ->join('users as u1', 'u1.id', '=', 'team_members.user_id')        
            ->join('users as u2', 'u2.id', '=', 'team_members.subscriber_id')        
            ->where('team_members.is_deleted',0)        
            ->where('td.is_deleted',0)                
            ->where('u1.is_deleted',0)                
            ->where('u2.is_deleted',0)                        
            ->select('team_members.*','u1.name as member_name','u2.name as subscriber_name','td.designation_name')        
            ->orderBy('team_members.id','ASC')
            ->paginate(50);
            
            return view('admin/team/team_member_list',array('title'=>'Team Member List','member_list'=>$member_list));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addTeamMember(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $designation_list = TeamDesignations::join('representation_area_list as ral', 'ral.id', '=', 'team_designations.rep_area_id')
            ->where('team_designations.subscriber_id',$user->id)                        
            ->where('team_designations.is_deleted',0)        
            ->where('ral.is_deleted',0)                
            ->select('team_designations.*','ral.representation_area')        
            ->orderBy('team_designations.id','ASC')
            ->get()->toArray();
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            $params = ['country_list'=>$country_list,'states_list'=>$states_list,'designation_list'=>$designation_list,'title'=>'Add Team Member'];
            
            return view('admin/team/team_member_add',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddTeamMember(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('emailAddress'=>'required','designation'=>'required','memberStatus'=>'required');
            $attributes = array('emailAddress'=>'Email Address','designation'=>'Designation','memberStatus'=>'Member Status','userName'=>'Name','mobileNumber'=>'Mobile Number','DOB'=>'DOB');
            
            $fields = ['country'=>'Country','state'=>'State','district'=>'District','LAC'=>'Legislative Assembly Constituency','PC'=>'Parliamentary Constituency',
            'MC1'=>'Municipal Corporation','MC2'=>'Municipality','CC'=>'City Council','block'=>'Block','ward'=>'Ward','subDistrict'=>'Sub District','village'=>'Village'];
            
            foreach($fields as $key=>$value){
                $attributes[$key.'_tm'] = $value;
            }
            
            if(!empty($data['email'])){
                $user_data = User::where('email',trim($data['email']))->where('is_deleted',0)->first();
                if(empty($user_data)){
                    $validationRules['userName'] = $validationRules['mobileNumber'] = $validationRules['gender'] = $validationRules['DOB'] = 'required';
                }
            }else{
                $validationRules['userName'] = $validationRules['mobileNumber'] = $validationRules['gender'] = $validationRules['DOB'] = 'required';
            }
            
            if(!empty($data['designation'])){
                $designation_data = TeamDesignations::join('representation_area_list as ral', 'ral.id', '=', 'team_designations.rep_area_id')
                ->where('team_designations.id',$data['designation'])  
                ->where('team_designations.is_deleted',0)        
                ->where('ral.is_deleted',0)                        
                ->select('team_designations.*','ral.representation_area','ral.rep_area_fields')        
                ->first();   
                
                $req_fields = explode(',',$designation_data->rep_area_fields);
                        
                for($i=0;$i<count($req_fields);$i++){
                    $field = $req_fields[$i].'_tm';
                    $validationRules[$field] = 'required';
                }
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $insertArray = array('designation_name'=>trim($data['designationName']),'rep_area_id'=>trim($data['representationArea']),'subscriber_id'=>$user->id);
       
            $designation = TeamDesignations::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Designation added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getTeamDesignationData(Request $request,$desig_id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $designation_data = TeamDesignations::join('representation_area_list as ral', 'ral.id', '=', 'team_designations.rep_area_id')
            ->where('team_designations.id',$desig_id)  
            ->where('team_designations.is_deleted',0)        
            ->where('ral.is_deleted',0)                        
            ->select('team_designations.*','ral.representation_area','ral.rep_area_fields')        
            ->first();   
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Designation Data','designation_data'=>$designation_data),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    
}
