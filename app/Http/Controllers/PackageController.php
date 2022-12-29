<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StateList;
use App\Models\CountryList;
use App\Models\DistrictList;
use App\Models\VillageList;
use App\Models\Packages;
use App\Models\PackagesPrice;
use App\Models\RepresentationAreaList;
use App\Models\User;
use App\Helpers\CommonHelper;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PackageController extends Controller
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

    public function addPackage(Request $request){
        try{
            $data = $request->all();
            
            $params = ['title'=>'Add Package'];
            
            return view('admin/package/package_add',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddPackage(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('packageName'=>'required','messageCallOption'=>'required','bulkMessageOption'=>'required','receiveSubmission'=>'required','packageValidity'=>'required','packageStatus'=>'required');
            
            $attributes = array('packageName'=>'Package Name','messageCallOption'=>'Message Call Option','bulkMessageOption'=>'Bulk Message Option','receiveSubmission'=>'Receive Submission',
            'packageValidity'=>'Package Validity','packageStatus'=>'Package Status','submissionRange'=>'Submission Range');
            
            if($data['receiveSubmission'] == 'yes'){
                $validationRules['submissionRange'] = 'required';
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $package_exists = Packages::where('package_name',trim($data['packageName']))->where('is_deleted',0)->first();
            if(!empty($designation_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Package already exists', 'errors' => 'Package already exists'));
            }
            
            $insertArray = array('package_name'=>trim($data['packageName']),'message_call'=>trim($data['messageCallOption']),'bulk_message'=>trim($data['bulkMessageOption']),
            'receive_submission'=>trim($data['receiveSubmission']),'package_validity'=>trim($data['packageValidity']),'status'=>trim($data['packageStatus']));
            
            $insertArray['receive_submission_range'] = ($data['receiveSubmission'] == 'yes')?$data['submissionRange']:null;
       
            $package = Packages::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Package added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editPackage(Request $request,$id){
        try{
            $data = $request->all();
            $package_id = $id;
            
            $package_data = Packages::where('id',$package_id)->first();
            
            $params = ['package_data'=>$package_data,'title'=>'Edit Package'];
            
            return view('admin/package/package_edit',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditPackage(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $package_id = $id;
            $package_data = Packages::where('id',$package_id)->first();
            
            $validationRules = array('packageName'=>'required','messageCallOption'=>'required','bulkMessageOption'=>'required','receiveSubmission'=>'required','packageValidity'=>'required','packageStatus'=>'required');
            
            $attributes = array('packageName'=>'Package Name','messageCallOption'=>'Message Call Option','bulkMessageOption'=>'Bulk Message Option','receiveSubmission'=>'Receive Submission',
            'packageValidity'=>'Package Validity','packageStatus'=>'Package Status','submissionRange'=>'Submission Range');
            
            if($data['receiveSubmission'] == 'yes'){
                $validationRules['submissionRange'] = 'required';
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $package_exists = Packages::where('package_name',trim($data['packageName']))->where('id','!=',$package_id)->where('is_deleted',0)->first();
            if(!empty($designation_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Package already exists', 'errors' => 'Package already exists'));
            }
            
            $updateArray = array('package_name'=>trim($data['packageName']),'message_call'=>trim($data['messageCallOption']),'bulk_message'=>trim($data['bulkMessageOption']),
            'receive_submission'=>trim($data['receiveSubmission']),'package_validity'=>trim($data['packageValidity']),'status'=>trim($data['packageStatus']));
            
            $updateArray['receive_submission_range'] = ($data['receiveSubmission'] == 'yes')?$data['submissionRange']:null;
            
            Packages::where('id',$package_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Package updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listPackage(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $package_list = Packages::where('is_deleted',0);                
            
            if(isset($data['name']) && !empty($data['name'])){
                $package_list = $package_list->where('package_name','LIKE','%'.trim($data['name']).'%');
            }
            
            $package_list = $package_list->select('*')        
            ->orderBy('id','ASC')
            ->paginate(50);
            
            return view('admin/package/package_list',array('title'=>'Package List','package_list'=>$package_list));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function updatePackage(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $ids = explode(',',trim($data['ids']));
            
            if(empty($data['ids'])){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Please select Package', 'errors' => 'Please select Package'));
            }
            
            if($data['action'] == 'delete'){
                Packages::wherein('id',$ids)->update(['is_deleted'=>1]);
            }
            
            if($data['action'] == 'disable'){
                Packages::wherein('id',$ids)->update(['status'=>0]);
            }
            
            if($data['action'] == 'enable'){
                Packages::wherein('id',$ids)->update(['status'=>1]);
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Packages updated successfully'),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    
    
}
