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
use App\Models\SubscriberList;
use App\Models\SubscriberPackage;
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
    
    function getPackageData(Request $request,$id){
        try{
            $data = $request->all();
            
            $package_data = Packages::where('id',$id)->first();         
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Package data','package_data'=>$package_data),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editPackagePrice(Request $request){
        try{
            $data = $request->all();
            
            $package_price_data = PackagesPrice::where('is_deleted',0)->first();
            
            $params = ['package_price_data'=>$package_price_data,'title'=>'Edit Package Price'];
            
            return view('admin/package/package_price_edit',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditPackagePrice(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('one_to_one_message_call'=>'required|numeric','group_message_call'=>'required|numeric','bulk_message'=>'required|numeric','country_range'=>'required|numeric','state_range'=>'required|numeric',
            'district_range'=>'required|numeric','pc_range'=>'required|numeric','ac_range'=>'required|numeric','discount_6_month'=>'required|numeric','discount_1_year'=>'required|numeric','discount_2_year'=>'required|numeric');
            
            $attributes = array('one_to_one_message_call'=>'One-to-one message/call option availability','group_message_call'=>'Group message/call option availability','bulk_message'=>'Bulk message option availability',
            'country_range'=>'If permitted range Country','state_range'=>'If permitted range State','district_range'=>'If permitted range District',
            'pc_range'=>'If permitted range Parliamentary Constituency','ac_range'=>'If permitted range Assembly Constituency','discount_6_month'=>'Discount for 6 months package',
            'discount_1_year'=>'Discount for 1 year package','discount_2_year'=>'Discount for2 year package');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $package_price_data = PackagesPrice::where('is_deleted',0)->first();
            
            $updateArray = array('one_to_one_message_call'=>trim($data['one_to_one_message_call']),'group_message_call'=>trim($data['group_message_call']),'bulk_message'=>trim($data['bulk_message']),
            'country_range'=>trim($data['country_range']),'state_range'=>trim($data['state_range']),'district_range'=>trim($data['district_range']),'pc_range'=>trim($data['pc_range']),
            'ac_range'=>trim($data['ac_range']),'discount_6_month'=>trim($data['discount_6_month']),'discount_1_year'=>trim($data['discount_1_year']),'discount_2_year'=>trim($data['discount_2_year'])    );
            
            if(!empty($package_price_data)){
                PackagesPrice::where('id',$package_price_data->id)->update($updateArray);
            }else{
                PackagesPrice::create($updateArray);
            }
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Package Price updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function listPackagePrice(Request $request){
        try{
            $data = $request->all();
            
            $package_price_data = PackagesPrice::where('is_deleted',0)->first();
            
            $params = ['package_price_data'=>$package_price_data,'title'=>'Package Price'];
            
            return view('admin/package/package_price_list',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addSubscriberPackage(Request $request){
        try{
            $data = $request->all();
            
            $subscriber_list = SubscriberList::join('users as u1', 'u1.id', '=', 'subscriber_list.user_id')    
            ->where('subscriber_list.is_deleted',0)
            ->where('u1.is_deleted',0) 
            ->select('subscriber_list.*','u1.name as subscriber_name')
            ->orderBy('subscriber_list.id')        
            ->get()->toArray();        
            
            $package_list = Packages::where('is_deleted',0)->orderBy('id')->get()->toArray();   
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            $params = ['title'=>'Add Subscriber Package','subscriber_list'=>$subscriber_list,'package_list'=>$package_list,'country_list'=>$country_list,'states_list'=>$states_list];
            
            return view('admin/package/subscriber_package_add',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddSubscriberPackage(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            $range = null;
            $requiredFields = [];
            
            $validationRules = array('subscriber'=>'required','package'=>'required','status'=>'required','discounted_price'=>'numeric');
            $attributes = array('pc'=>'Parliamentary Constituency','ac'=>'Assembly Constituency');
            
            $rangeData = ['country'=>'country','state'=>'country,state','district'=>'country,state,district','pc'=>'country,state,district,pc','ac'=>'country,state,district,ac'];
            
            if(isset($data['package']) && !empty($data['package'])){
                $package_data = Packages::where('id',$data['package'])->first();   
                if($package_data->receive_submission == 'yes'){
                    $range = $package_data->receive_submission_range;
                    $requiredFields  = explode(',',$rangeData[$range]); ;
                    
                    for($i=0;$i<count($requiredFields);$i++){
                        $validationRules[$requiredFields[$i]] = 'required';
                    }
                }
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $subscriber_package_exists = SubscriberPackage::where('subscriber_id',trim($data['subscriber']))->where('package_id',trim($data['package']))->where('is_deleted',0)->first();
            if(!empty($subscriber_package_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Package already exists for Subscriber', 'errors' => 'Package already exists for Subscriber'));
            }
            
            $discounted_price = !empty($data['discounted_price'])?$data['discounted_price']:null;
            $insertArray = array('subscriber_id'=>trim($data['subscriber']),'package_id'=>trim($data['package']),'submission_range'=>$range,'discounted_price'=>$discounted_price,'status'=>trim($data['status']));
            
            for($i=0;$i<count($requiredFields);$i++){
                $field = $requiredFields[$i];
                $insertArray[$field] = is_array($data[$field])?implode(',',$data[$field]):$data[$field];
            }
            
            $package = SubscriberPackage::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscriber Package added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editSubscriberPackage(Request $request,$id){
        try{
            $data = $request->all();
            
            $subscriber_package_id = $id;
            $subscriber_package_data = SubscriberPackage::where('id',$subscriber_package_id)->first();
                    
            $subscriber_list = SubscriberList::join('users as u1', 'u1.id', '=', 'subscriber_list.user_id')    
            ->where('subscriber_list.is_deleted',0)
            ->where('u1.is_deleted',0) 
            ->select('subscriber_list.*','u1.name as subscriber_name')
            ->orderBy('subscriber_list.id')        
            ->get()->toArray();        
            
            $package_list = Packages::where('is_deleted',0)->orderBy('id')->get()->toArray();   
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            
            $params = ['title'=>'Edit Subscriber Package','subscriber_list'=>$subscriber_list,'package_list'=>$package_list,'country_list'=>$country_list,'states_list'=>$states_list,'subscriber_package_data'=>$subscriber_package_data];
            
            return view('admin/package/subscriber_package_edit',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditSubscriberPackage(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            $range = null;
            $requiredFields = [];
            
            $subscriber_package_id = $id;
            $subscriber_package_data = SubscriberPackage::where('id',$subscriber_package_id)->first();
            
            $validationRules = array('subscriber'=>'required','package'=>'required','status'=>'required','discounted_price'=>'numeric');
            $attributes = array('pc'=>'Parliamentary Constituency','ac'=>'Assembly Constituency');
            
            $rangeData = ['country'=>'country','state'=>'country,state','district'=>'country,state,district','pc'=>'country,state,district,pc','ac'=>'country,state,district,ac'];
            
            if(isset($data['package']) && !empty($data['package'])){
                $package_data = Packages::where('id',$data['package'])->first();   
                if($package_data->receive_submission == 'yes'){
                    $range = $package_data->receive_submission_range;
                    $requiredFields  = explode(',',$rangeData[$range]); ;
                    
                    for($i=0;$i<count($requiredFields);$i++){
                        $validationRules[$requiredFields[$i]] = 'required';
                    }
                }
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $subscriber_package_exists = SubscriberPackage::where('subscriber_id',trim($data['subscriber']))->where('package_id',trim($data['package']))->where('id','!=',trim($subscriber_package_id))->where('is_deleted',0)->first();
            if(!empty($subscriber_package_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Package already exists for Subscriber', 'errors' => 'Package already exists for Subscriber'));
            }
            
            $discounted_price = !empty($data['discounted_price'])?$data['discounted_price']:null;
            $updateArray = array('subscriber_id'=>trim($data['subscriber']),'package_id'=>trim($data['package']),'submission_range'=>$range,'discounted_price'=>$discounted_price,'status'=>trim($data['status']));
            
            for($i=0;$i<count($requiredFields);$i++){
                $field = $requiredFields[$i];
                $updateArray[$field] = $data[$field];
            }
            
            SubscriberPackage::where('id',$subscriber_package_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscriber Package updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listSubscriberPackage(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $subscriber_package_list = SubscriberPackage::join('subscriber_list as s', 's.id', '=', 'subscriber_package.subscriber_id')
            ->join('users as u1', 'u1.id', '=', 's.user_id')        
            ->join('packages as p', 'p.id', '=', 'subscriber_package.package_id')        
            ->where('subscriber_package.is_deleted',0)
            ->where('s.is_deleted',0)
            ->where('u1.is_deleted',0)
            ->where('p.is_deleted',0);               
            
            if(isset($data['name']) && !empty($data['name'])){
                $subscriber_package_list = $subscriber_package_list->where('u1.name','LIKE','%'.trim($data['name']).'%');
            }
            
            $subscriber_package_list = $subscriber_package_list->select('subscriber_package.*','u1.name as subscriber_name','p.package_name')        
            ->orderBy('subscriber_package.id','ASC')
            ->paginate(50);
            
            return view('admin/package/subscriber_package_list',array('title'=>'Subscriber Package List','subscriber_package_list'=>$subscriber_package_list));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function updateSubscriberPackage(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $ids = explode(',',trim($data['ids']));
            
            if(empty($data['ids'])){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Please select Subscriber Package', 'errors' => 'Please select Subscriber Package'));
            }
            
            if($data['action'] == 'delete'){
                SubscriberPackage::wherein('id',$ids)->update(['is_deleted'=>1]);
            }
            
            if($data['action'] == 'disable'){
                SubscriberPackage::wherein('id',$ids)->update(['status'=>0]);
            }
            
            if($data['action'] == 'enable'){
                SubscriberPackage::wherein('id',$ids)->update(['status'=>1]);
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscriber Packages updated successfully'),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
}
