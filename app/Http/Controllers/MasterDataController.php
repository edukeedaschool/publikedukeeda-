<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
use App\Helpers\CommonHelper;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    function listStates(Request $request){
        try{
            $data = $request->all();
            $states_list = [];
            $user = Auth::user();
            
            $states_list = StateList::where('is_deleted',0)->select('*');
            
            //  filter
            if(isset($data['query']) && !empty($data['query'])){
                $query = trim($data['query']);
                $states_list  = $states_list->whereRaw("(state_name LIKE '%$query%')");
            }
            
            $states_list = $states_list->orderBy('id','ASC')->paginate(50);
            
            return view('admin/master_data/states_list',array('states_list'=>$states_list,'title'=>'States List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddState(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('stateName'=>'required','stateStatus'=>'required','stateCountry'=>'required');
            $attributes = array('stateName'=>'State Name','stateStatus'=>'State Status','stateCountry'=>'Country');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $state_exists = StateList::where('state_name',trim($data['stateName']))->where('is_deleted',0)->first();
            if(!empty($state_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'State already exists in system', 'errors' => 'State already exists in system'));
            }
            
            $insertArray = array('state_name'=>trim($data['stateName']),'status'=>trim($data['stateStatus']),'country_id'=>trim($data['stateCountry']));
       
            $state = StateList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'State added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addState(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/state_add',array('country_list'=>$country_list,'title'=>'Add State'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function editState(Request $request,$id){
        try{
            $data = $request->all();
            $state_id = $id;
            
            $state_data = StateList::where('id',$state_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/state_edit',array('state_data'=>$state_data,'country_list'=>$country_list,'title'=>'Edit State'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditState(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $state_id = $id;
            $state_data = StateList::where('id',$state_id)->where('is_deleted',0)->first();
            
            $validationRules = array('stateName'=>'required','stateStatus'=>'required','stateCountry'=>'required');
            $attributes = array('stateName'=>'State Name','stateStatus'=>'State Status','stateCountry'=>'Country');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $state_exists = StateList::where('state_name',trim($data['stateName']))->where('is_deleted',0)->where('id','!=',$state_data->id)->first();
            if(!empty($state_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'State already exists in system', 'errors' => 'State already exists in system'));
            }
            
            $updateArray = array('state_name'=>trim($data['stateName']),'status'=>trim($data['stateStatus']),'country_id'=>trim($data['stateCountry']));
       
            StateList::where('id',$state_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'State updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    
    
    function listDistricts(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $districts_list = DistrictList::join('state_list as s', 's.id', '=', 'district_list.state_id')
            ->where('district_list.is_deleted',0)
            ->where('s.is_deleted',0);        
            
            if(isset($data['dis_name']) && !empty($data['dis_name'])){
                $districts_list = $districts_list->where('district_list.district_name','LIKE','%'.trim($data['dis_name']).'%');
            }
            
            $districts_list = $districts_list->select('district_list.*','s.state_name')        
            ->orderBy('district_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/districts_list',array('districts_list'=>$districts_list,'title'=>'Districts List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddDistrict(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('districtName'=>'required','districtStatus'=>'required','districtCountry'=>'required','districtState'=>'required');
            $attributes = array('districtName'=>'District Name','districtStatus'=>'District Status','districtCountry'=>'Country','districtState'=>'State');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $district_exists = DistrictList::where('district_name',trim($data['districtName']))->where('state_id',trim($data['districtState']))->where('is_deleted',0)->first();
            if(!empty($district_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'District already exists in State', 'errors' => 'District already exists in State'));
            }
            
            $insertArray = array('district_name'=>trim($data['districtName']),'state_id'=>trim($data['districtState']),'status'=>trim($data['districtStatus']),'country_id'=>trim($data['districtCountry']));
       
            $district = DistrictList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'District added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addDistrict(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/district_add',array('country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add District'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function editDistrict(Request $request,$id){
        try{
            $data = $request->all();
            $district_id = $id;
            
            $district_data = DistrictList::where('id',$district_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $state_data = StateList::where('id',$district_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/district_edit',array('district_data'=>$district_data,'country_list'=>$country_list,'states_list'=>$states_list,'state_data'=>$state_data,'title'=>'Edit District'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditDistrict(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $district_id = $id;
            $district_data = DistrictList::where('id',$district_id)->where('is_deleted',0)->first();
            
            $validationRules = array('districtName'=>'required','districtStatus'=>'required','districtCountry'=>'required','districtState'=>'required');
            $attributes = array('districtName'=>'District Name','districtStatus'=>'District Status','districtCountry'=>'Country','districtState'=>'State');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $district_exists = DistrictList::where('district_name',trim($data['districtName']))->where('state_id',trim($data['districtState']))->where('id','!=',$district_id)->where('is_deleted',0)->first();
            if(!empty($district_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'District already exists in State', 'errors' => 'District already exists in State'));
            }
            
            $updateArray = array('district_name'=>trim($data['districtName']),'state_id'=>trim($data['districtState']),'status'=>trim($data['districtStatus']));
       
            DistrictList::where('id',$district_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'District updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listMC1(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $mc1_list = Mc1List::join('state_list as s', 's.id', '=', 'mc_mahanagar_palika.state_id')
            ->where('mc_mahanagar_palika.is_deleted',0)
            ->where('s.is_deleted',0);        
            
            if(isset($data['mc1_name']) && !empty($data['mc1_name'])){
                $mc1_list = $mc1_list->where('mc_mahanagar_palika.mc_name','LIKE','%'.trim($data['mc1_name']).'%');
            }
            
            $mc1_list = $mc1_list->select('mc_mahanagar_palika.*','s.state_name')        
            ->orderBy('mc_mahanagar_palika.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/mc1_list',array('mc1_list'=>$mc1_list,'title'=>'Municipal corporation (Mahanagar Palika) List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddMc1(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('mcName'=>'required','mcStatus'=>'required','mcCountry'=>'required','mcState'=>'required');
            $attributes = array('mcName'=>'MC Name','mcStatus'=>'MC Status','mcCountry'=>'Country','mcState'=>'State');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $mc_exists = Mc1List::where('mc_name',trim($data['mcName']))->where('state_id',trim($data['mcState']))->where('is_deleted',0)->first();
            if(!empty($mc_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'MC already exists in State', 'errors' => 'MC already exists in State'));
            }
            
            $insertArray = array('mc_name'=>trim($data['mcName']),'state_id'=>trim($data['mcState']),'status'=>trim($data['mcStatus']));
       
            $mc = Mc1List::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'MC added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addMc1(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/mc1_add',array('country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add Municipal Corporation (Mahanagar Palika)'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function editMc1(Request $request,$id){
        try{
            $data = $request->all();
            $mc_id = $id;
            
            $mc_data = Mc1List::where('id',$mc_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $state_data = StateList::where('id',$mc_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/mc1_edit',array('mc_data'=>$mc_data,'country_list'=>$country_list,'states_list'=>$states_list,'state_data'=>$state_data,'title'=>'Edit Municipal Corporation (Mahanagar Palika)'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditMc1(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $mc_id = $id;
            $mc_data = Mc1List::where('id',$mc_id)->where('is_deleted',0)->first();
            
            $validationRules = array('mcName'=>'required','mcStatus'=>'required','mcCountry'=>'required','mcState'=>'required');
            $attributes = array('mcName'=>'MC Name','mcStatus'=>'MC Status','mcCountry'=>'Country','mcState'=>'State');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $mc_exists = Mc1List::where('mc_name',trim($data['mcName']))->where('state_id',trim($data['mcState']))->where('id','!=',$mc_id)->where('is_deleted',0)->first();
            if(!empty($mc_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'MC already exists in State', 'errors' => 'MC already exists in State'));
            }
            
            $updateArray = array('mc_name'=>trim($data['mcName']),'state_id'=>trim($data['mcState']),'status'=>trim($data['mcStatus']));
       
            Mc1List::where('id',$mc_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'District updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listMC2(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $mc2_list = Mc2List::join('district_list as d', 'd.id', '=', 'mc_nagar_palika.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('mc_nagar_palika.is_deleted',0)
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);
            
            if(isset($data['mc2_name']) && !empty($data['mc2_name'])){
                $mc2_list = $mc2_list->where('mc_nagar_palika.mc_name','LIKE','%'.trim($data['mc2_name']).'%');
            }
            
            $mc2_list = $mc2_list->select('mc_nagar_palika.*','s.state_name','d.district_name')        
            ->orderBy('mc_nagar_palika.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/mc2_list',array('mc2_list'=>$mc2_list,'title'=>'Municipal Corporation (Nagar Palika) List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    
    public function submitAddMc2(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('mcName'=>'required','mcStatus'=>'required','mcCountry'=>'required','mcState'=>'required','mcDistrict'=>'required');
            $attributes = array('mcName'=>'MC Name','mcStatus'=>'MC Status','mcCountry'=>'Country','mcState'=>'State','mcDistrict'=>'District');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $mc_exists = Mc2List::where('mc_name',trim($data['mcName']))->where('district_id',trim($data['mcDistrict']))->where('is_deleted',0)->first();
            if(!empty($mc_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'MC already exists in District', 'errors' => 'MC already exists in District'));
            }
            
            $insertArray = array('mc_name'=>trim($data['mcName']),'district_id'=>trim($data['mcDistrict']),'status'=>trim($data['mcStatus']));
       
            $mc = Mc2List::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'MC added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addMc2(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/mc2_add',array('country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add Municipal Corporation (Nagar Palika)'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function getDistrictList(Request $request,$state_id){
        try{
            $data = $request->all();
            $district_list = DistrictList::where('state_id',$state_id)->where('is_deleted',0)->orderBy('district_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'District List','district_list'=>$district_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editMc2(Request $request,$id){
        try{
            $data = $request->all();
            $mc_id = $id;
            
            $mc_data = Mc2List::where('id',$mc_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $district_data = DistrictList::where('id',$mc_data->district_id)->where('is_deleted',0)->first();
            $state_data = StateList::where('id',$district_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/mc2_edit',array('mc_data'=>$mc_data,'country_list'=>$country_list,'states_list'=>$states_list,'state_data'=>$state_data,'title'=>'Edit Municipal Corporation (Nagar Palika)'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditMc2(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $mc_id = $id;
            $mc_data = Mc2List::where('id',$mc_id)->where('is_deleted',0)->first();
            
            $validationRules = array('mcName'=>'required','mcStatus'=>'required','mcCountry'=>'required','mcState'=>'required','mcDistrict'=>'required');
            $attributes = array('mcName'=>'MC Name','mcStatus'=>'MC Status','mcCountry'=>'Country','mcState'=>'State','mcDistrict'=>'District');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $mc_exists = Mc2List::where('mc_name',trim($data['mcName']))->where('district_id',trim($data['mcDistrict']))->where('id','!=',$mc_id)->where('is_deleted',0)->first();
            if(!empty($mc_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'MC already exists in District', 'errors' => 'MC already exists in District'));
            }
            
            $updateArray = array('mc_name'=>trim($data['mcName']),'district_id'=>trim($data['mcDistrict']),'status'=>trim($data['mcStatus']));
       
            Mc2List::where('id',$mc_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'MC updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listCityCouncil(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $city_council_list = CityCouncil::join('district_list as d', 'd.id', '=', 'city_council.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('city_council.is_deleted',0)
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);  
            
            if(isset($data['cc_name']) && !empty($data['cc_name'])){
                $city_council_list = $city_council_list->where('city_council.city_council_name','LIKE','%'.trim($data['cc_name']).'%');
            }
            
            $city_council_list = $city_council_list->select('city_council.*','s.state_name','d.district_name')        
            ->orderBy('city_council.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/city_council_list',array('city_council_list'=>$city_council_list,'title'=>'City Council List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddCityCouncil(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('ccName'=>'required','ccStatus'=>'required','ccCountry'=>'required','ccState'=>'required','ccDistrict'=>'required');
            $attributes = array('ccName'=>'City Council Name','ccStatus'=>'City Council Status','ccCountry'=>'Country','ccState'=>'State','ccDistrict'=>'District');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $cc_exists = CityCouncil::where('city_council_name',trim($data['ccName']))->where('district_id',trim($data['ccDistrict']))->where('is_deleted',0)->first();
            if(!empty($cc_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'City Council already exists in District', 'errors' => 'CityCouncil already exists in District'));
            }
            
            $insertArray = array('city_council_name'=>trim($data['ccName']),'district_id'=>trim($data['ccDistrict']),'status'=>trim($data['ccStatus']));
       
            $cc = CityCouncil::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'City Council added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addCityCouncil(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/city_council_add',array('country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add City Council'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function editCityCouncil(Request $request,$id){
        try{
            $data = $request->all();
            $cc_id = $id;
            
            $cc_data = CityCouncil::where('id',$cc_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $district_data = DistrictList::where('id',$cc_data->district_id)->where('is_deleted',0)->first();
            $state_data = StateList::where('id',$district_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/city_council_edit',array('cc_data'=>$cc_data,'country_list'=>$country_list,'states_list'=>$states_list,'state_data'=>$state_data,'title'=>'Edit City Council'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditCityCouncil(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $cc_id = $id;
            $cc_data = CityCouncil::where('id',$cc_id)->where('is_deleted',0)->first();
            
            $validationRules = array('ccName'=>'required','ccStatus'=>'required','ccCountry'=>'required','ccState'=>'required','ccDistrict'=>'required');
            $attributes = array('ccName'=>'City Council Name','ccStatus'=>'City Council Status','ccCountry'=>'Country','ccState'=>'State','ccDistrict'=>'District');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $cc_exists = CityCouncil::where('city_council_name',trim($data['ccName']))->where('district_id',trim($data['ccDistrict']))->where('id','!=',$cc_id)->where('is_deleted',0)->first();
            if(!empty($cc_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'City Council already exists in District', 'errors' => 'City Council already exists in District'));
            }
            
            $updateArray = array('city_council_name'=>trim($data['ccName']),'district_id'=>trim($data['ccDistrict']),'status'=>trim($data['ccStatus']));
       
            CityCouncil::where('id',$cc_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'City Council updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listBlock(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $block_list = BlockList::join('district_list as d', 'd.id', '=', 'block_list.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('block_list.is_deleted',0)
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);          
            
            if(isset($data['block_name']) && !empty($data['block_name'])){
                $block_list = $block_list->where('block_list.block_name','LIKE','%'.trim($data['block_name']).'%');
            }
            
            $block_list = $block_list->select('block_list.*','s.state_name','d.district_name')        
            ->orderBy('block_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/block_list',array('block_list'=>$block_list,'title'=>'Block List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddBlock(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $validationRules = array('blockName'=>'required','blockStatus'=>'required','blockCountry'=>'required','blockState'=>'required','blockDistrict'=>'required');
            $attributes = array('blockName'=>'Block Name','blockStatus'=>'Block Status','blockCountry'=>'Country','blockState'=>'State','blockDistrict'=>'District');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $block_exists = BlockList::where('block_name',trim($data['blockName']))->where('district_id',trim($data['blockDistrict']))->where('is_deleted',0)->first();
            if(!empty($block_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Block already exists in District', 'errors' => 'Block already exists in District'));
            }
            
            $insertArray = array('block_name'=>trim($data['blockName']),'district_id'=>trim($data['blockDistrict']),'status'=>trim($data['blockStatus']));
       
            $block = BlockList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Block added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addBlock(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/block_add',array('country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add Block'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function editBlock(Request $request,$id){
        try{
            $data = $request->all();
            $block_id = $id;
            
            $block_data = BlockList::where('id',$block_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $district_data = DistrictList::where('id',$block_data->district_id)->where('is_deleted',0)->first();
            $state_data = StateList::where('id',$district_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/block_edit',array('block_data'=>$block_data,'country_list'=>$country_list,'states_list'=>$states_list,'state_data'=>$state_data,'title'=>'Edit Block'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditBlock(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $block_id = $id;
            $block_data = BlockList::where('id',$block_id)->where('is_deleted',0)->first();
            
            $validationRules = array('blockName'=>'required','blockStatus'=>'required','blockCountry'=>'required','blockState'=>'required','blockDistrict'=>'required');
            $attributes = array('blockName'=>'Block Name','blockStatus'=>'Block Status','blockCountry'=>'Country','blockState'=>'State','blockDistrict'=>'District');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $block_exists = BlockList::where('block_name',trim($data['blockName']))->where('district_id',trim($data['blockDistrict']))->where('id','!=',$block_id)->where('is_deleted',0)->first();
            
            if(!empty($block_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Block already exists in District', 'errors' => 'Block already exists in District'));
            }
            
            $updateArray = array('block_name'=>trim($data['blockName']),'district_id'=>trim($data['blockDistrict']),'status'=>trim($data['blockStatus']));
       
            BlockList::where('id',$block_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Block updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listSubDistrict(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $sub_district_list = SubDistrictList::join('district_list as d', 'd.id', '=', 'sub_district_list.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('sub_district_list.is_deleted',0)
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);
            
            if(isset($data['sd_name']) && !empty($data['sd_name'])){
                $sub_district_list = $sub_district_list->where('sub_district_list.sub_district_name','LIKE','%'.trim($data['sd_name']).'%');
            }
            
            $sub_district_list = $sub_district_list->select('sub_district_list.*','s.state_name','d.district_name')        
            ->orderBy('sub_district_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/sub_district_list',array('sub_district_list'=>$sub_district_list,'title'=>'Sub District List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddSubDistrict(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('sdName'=>'required','sdStatus'=>'required','sdCountry'=>'required','sdState'=>'required','sdDistrict'=>'required');
            $attributes = array('sdName'=>'Sub District Name','sdStatus'=>'Sub District Status','sdCountry'=>'Country','sdState'=>'State','sdDistrict'=>'District');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $sd_exists = SubDistrictList::where('sub_district_name',trim($data['sdName']))->where('district_id',trim($data['sdDistrict']))->where('is_deleted',0)->first();
            if(!empty($sd_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Sub District already exists in District', 'errors' => 'Sub District already exists in District'));
            }
            
            $insertArray = array('sub_district_name'=>trim($data['sdName']),'district_id'=>trim($data['sdDistrict']),'status'=>trim($data['sdStatus']));
       
            $sd = SubDistrictList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Sub District added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addSubDistrict(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/sub_district_add',array('country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add Sub District'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function editSubDistrict(Request $request,$id){
        try{
            $data = $request->all();
            $sub_district_id = $id;
            
            $sd_data = SubDistrictList::where('id',$sub_district_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $district_data = DistrictList::where('id',$sd_data->district_id)->where('is_deleted',0)->first();
            $state_data = StateList::where('id',$district_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/sub_district_edit',array('sd_data'=>$sd_data,'country_list'=>$country_list,'states_list'=>$states_list,'state_data'=>$state_data,'title'=>'Edit Sub District'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditSubDistrict(Request $request,$id){
        try{
            $data = $request->all();
            
            $sub_district_id = $id;
            $sd_data = SubDistrictList::where('id',$sub_district_id)->where('is_deleted',0)->first();
            
            $validationRules = array('sdName'=>'required','sdStatus'=>'required','sdCountry'=>'required','sdState'=>'required','sdDistrict'=>'required');
            $attributes = array('sdName'=>'Sub District Name','sdStatus'=>'Sub District Status','sdCountry'=>'Country','sdState'=>'State','sdDistrict'=>'District');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $sd_exists = SubDistrictList::where('sub_district_name',trim($data['sdName']))->where('district_id',trim($data['sdDistrict']))->where('id','!=',$sub_district_id)->where('is_deleted',0)->first();
            
            if(!empty($sd_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Sub District already exists in District', 'errors' => 'Sub District already exists in District'));
            }
            
            $updateArray = array('sub_district_name'=>trim($data['sdName']),'district_id'=>trim($data['sdDistrict']),'status'=>trim($data['sdStatus']));
       
            SubDistrictList::where('id',$sub_district_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Sub District updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listWard(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $ward_list = WardList::join('district_list as d', 'd.id', '=', 'ward_list.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->join('mc_mahanagar_palika as mc1', 'mc1.id', '=', 'ward_list.mc1_id')
            ->join('mc_nagar_palika as mc2', 'mc2.id', '=', 'ward_list.mc2_id')        
            ->join('city_council as cc', 'cc.id', '=', 'ward_list.city_council_id')
            ->where('ward_list.is_deleted',0)
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);           
            
            if(isset($data['ward_name']) && !empty($data['ward_name'])){
                $ward_list = $ward_list->where('ward_list.ward_name','LIKE','%'.trim($data['ward_name']).'%');
            }
            
            $ward_list = $ward_list->select('ward_list.*','s.state_name','d.district_name','mc1.mc_name as mc1_name','mc2.mc_name as mc2_name','cc.city_council_name')        
            ->orderBy('ward_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/ward_list',array('ward_list'=>$ward_list,'title'=>'Ward List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddWard(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('wardName'=>'required','wardStatus'=>'required','wardCountry'=>'required','wardState'=>'required','wardDistrict'=>'required','wardMC1'=>'required','wardMC2'=>'required','wardCC'=>'required');
            $attributes = array('wardName'=>'Ward Name','wardStatus'=>'Ward Status','wardCountry'=>'Country','wardState'=>'State','wardDistrict'=>'District','wardMC1'=>'Municipal Corporation','wardMC2'=>'Municipality','wardCC'=>'City Council');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $ward_exists = WardList::where('ward_name',trim($data['wardName']))->where('district_id',trim($data['wardDistrict']))->where('is_deleted',0)->first();
            if(!empty($ward_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Ward already exists in District', 'errors' => 'Ward already exists in District'));
            }
            
            $insertArray = array('ward_name'=>trim($data['wardName']),'district_id'=>trim($data['wardDistrict']),'mc1_id'=>trim($data['wardMC1']),'mc2_id'=>trim($data['wardMC2']),'city_council_id'=>trim($data['wardCC']),'status'=>trim($data['wardStatus']));
       
            $ward = WardList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Ward added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editWard(Request $request,$id){
        try{
            $data = $request->all();
            $ward_id = $id;
            
            $ward_data = WardList::where('id',$ward_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $district_data = DistrictList::where('id',$ward_data->district_id)->where('is_deleted',0)->first();
            $state_data = StateList::where('id',$district_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/ward_edit',array('ward_data'=>$ward_data,'country_list'=>$country_list,'states_list'=>$states_list,'state_data'=>$state_data,'title'=>'Edit Ward'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditWard(Request $request,$id){
        try{
            $data = $request->all();
            
            $ward_id = $id;
            $ward_data = WardList::where('id',$ward_id)->where('is_deleted',0)->first();
            
            $validationRules = array('wardName'=>'required','wardStatus'=>'required','wardCountry'=>'required','wardState'=>'required','wardDistrict'=>'required','wardMC1'=>'required','wardMC2'=>'required','wardCC'=>'required');
            $attributes = array('wardName'=>'Ward Name','wardStatus'=>'Ward Status','wardCountry'=>'Country','wardState'=>'State','wardDistrict'=>'District','wardMC1'=>'Municipal Corporation','wardMC2'=>'Municipality','wardCC'=>'City Council');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $ward_exists = WardList::where('ward_name',trim($data['wardName']))->where('district_id',trim($data['wardDistrict']))->where('id','!=',$ward_id)->where('is_deleted',0)->first();
            
            if(!empty($ward_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Ward already exists in District', 'errors' => 'Ward already exists in District'));
            }
            
            $updateArray = array('ward_name'=>trim($data['wardName']),'district_id'=>trim($data['wardDistrict']),'mc1_id'=>trim($data['wardMC1']),'mc2_id'=>trim($data['wardMC2']),'city_council_id'=>trim($data['wardCC']),'status'=>trim($data['wardStatus']));
       
            WardList::where('id',$ward_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Ward updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addWard(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/ward_add',array('country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add Ward'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function getMC1List(Request $request,$state_id){
        try{
            $data = $request->all();
            $mc1_list = Mc1List::where('state_id',$state_id)->where('is_deleted',0)->orderBy('mc_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'MC1 List','mc1_list'=>$mc1_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getMC2List(Request $request,$district_id){
        try{
            $data = $request->all();
            $mc2_list = Mc2List::where('district_id',$district_id)->where('is_deleted',0)->orderBy('mc_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'MC2 List','mc2_list'=>$mc2_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getCityCouncilList(Request $request,$district_id){
        try{
            $data = $request->all();
            $cc_list = CityCouncil::where('district_id',$district_id)->where('is_deleted',0)->orderBy('city_council_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'City Council List','cc_list'=>$cc_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getSubDistrictList(Request $request,$district_id){
        try{
            $data = $request->all();
            $sub_district_list = SubDistrictList::where('district_id',$district_id)->where('is_deleted',0)->orderBy('sub_district_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Sub District List','sub_district_list'=>$sub_district_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getBlockList(Request $request,$district_id){
        try{
            $data = $request->all();
            $block_list = BlockList::where('district_id',$district_id)->where('is_deleted',0)->orderBy('block_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Block List','block_list'=>$block_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getVillageList(Request $request,$sub_district_id){
        try{
            $data = $request->all();
            $village_list = VillageList::where('sub_district_id',$sub_district_id)->where('is_deleted',0)->orderBy('village_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Village List','village_list'=>$village_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getWardList(Request $request,$city_council_id){
        try{
            $data = $request->all();
            $ward_list = WardList::where('city_council_id',$city_council_id)->where('is_deleted',0)->orderBy('ward_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Ward List','ward_list'=>$ward_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getLACList(Request $request,$district_id){
        try{
            $data = $request->all();
            $lac_list = LegislativeAssemblyConstituency::whereRaw("FIND_IN_SET($district_id,district_list)")->where('is_deleted',0)->orderBy('constituency_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'LAC List','lac_list'=>$lac_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getPCList(Request $request,$district_id){
        try{
            $data = $request->all();
            $pc_list = ParliamentaryConstituency::whereRaw("FIND_IN_SET($district_id,district_list)")->where('is_deleted',0)->orderBy('constituency_name')->get()->toArray();
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'PC List','pc_list'=>$pc_list),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listVillage(Request $request){
        try{
            $data = $request->all();
            
            $village_list = VillageList::join('sub_district_list as sd', 'sd.id', '=', 'village_list.sub_district_id')
            ->join('district_list as d', 'd.id', '=', 'sd.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('village_list.is_deleted',0)
            ->where('sd.is_deleted',0)                
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);
            
            if(isset($data['village_name']) && !empty($data['village_name'])){
                $village_list = $village_list->where('village_list.village_name','LIKE','%'.trim($data['village_name']).'%');
            }
            
            $village_list = $village_list->select('village_list.*','s.state_name','d.district_name','sd.sub_district_name')        
            ->orderBy('village_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/village_list',array('village_list'=>$village_list,'title'=>'Village List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addVillage(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/village_add',array('country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add Village'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddVillage(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('villageName'=>'required','villageStatus'=>'required','villageCountry'=>'required','villageState'=>'required','villageDistrict'=>'required','villageSubDistrict'=>'required');
            $attributes = array('villageName'=>'Village Name','villageStatus'=>'Village Status','villageCountry'=>'Country','villageState'=>'State','villageDistrict'=>'District','villageSubDistrict'=>'SubDistrict');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $village_exists = VillageList::where('village_name',trim($data['villageName']))->where('sub_district_id',trim($data['villageSubDistrict']))->where('is_deleted',0)->first();
            if(!empty($village_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Village already exists in SubDistrict', 'errors' => 'Village already exists in SubDistrict'));
            }
            
            $insertArray = array('village_name'=>trim($data['villageName']),'sub_district_id'=>trim($data['villageSubDistrict']),'status'=>trim($data['villageStatus']));
       
            $village = VillageList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Village added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editVillage(Request $request,$id){
        try{
            $data = $request->all();
            $village_id = $id;
            
            $village_data = VillageList::where('id',$village_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $sub_district_data = SubDistrictList::where('id',$village_data->sub_district_id)->where('is_deleted',0)->first();
            $district_data = DistrictList::where('id',$sub_district_data->district_id)->where('is_deleted',0)->first();
            $state_data = StateList::where('id',$district_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/village_edit',array('village_data'=>$village_data,'country_list'=>$country_list,'states_list'=>$states_list,'state_data'=>$state_data,'sub_district_data'=>$sub_district_data,'district_data'=>$district_data,'title'=>'Edit Village'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditVillage(Request $request,$id){
        try{
            $data = $request->all();
            
            $village_id = $id;
            $village_data = VillageList::where('id',$village_id)->where('is_deleted',0)->first();
            
            $validationRules = array('villageName'=>'required','villageStatus'=>'required','villageCountry'=>'required','villageState'=>'required','villageDistrict'=>'required','villageSubDistrict'=>'required');
            $attributes = array('villageName'=>'Village Name','villageStatus'=>'Village Status','villageCountry'=>'Country','villageState'=>'State','villageDistrict'=>'District','villageSubDistrict'=>'SubDistrict');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $village_exists = VillageList::where('village_name',trim($data['villageName']))->where('sub_district_id',trim($data['villageSubDistrict']))->where('id','!=',$village_id)->where('is_deleted',0)->first();
            
            if(!empty($village_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Village already exists in SubDistrict', 'errors' => 'Village already exists in SubDistrict'));
            }
            
            $updateArray = array('village_name'=>trim($data['villageName']),'sub_district_id'=>trim($data['villageSubDistrict']),'status'=>trim($data['villageStatus']));
       
            VillageList::where('id',$village_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Village updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    
    function listPostalCode(Request $request){
        try{
            $data = $request->all();
            
            $postal_code_list = PostalCodeList::join('sub_district_list as sd', 'sd.id', '=', 'postal_code_list.sub_district_id')
            ->join('district_list as d', 'd.id', '=', 'sd.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('postal_code_list.is_deleted',0)
            ->where('sd.is_deleted',0)                
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);
            
            if(isset($data['postal_code']) && !empty($data['postal_code'])){
                $postal_code_list = $postal_code_list->where('postal_code_list.postal_code','LIKE','%'.trim($data['postal_code']).'%');
            }
            
            $postal_code_list = $postal_code_list->select('postal_code_list.*','s.state_name','d.district_name','sd.sub_district_name')        
            ->orderBy('postal_code_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/postal_code_list',array('postal_code_list'=>$postal_code_list,'title'=>'Postal Code List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addPostalCode(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/postal_code_add',array('country_list'=>$country_list,'states_list'=>$states_list,'title'=>'Add Postal Code'));
            
        }catch (\Exception $e){
             return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddPostalCode(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('pcName'=>'required','pcStatus'=>'required','pcCountry'=>'required','pcState'=>'required','pcDistrict'=>'required','pcSubDistrict'=>'required','pcPostOffice'=>'required');
            $attributes = array('pcName'=>'Postal Code Name','pcStatus'=>'Postal Code Status','pcCountry'=>'Country','pcState'=>'State','pcDistrict'=>'District','pcSubDistrict'=>'Sub District','pcPostOffice'=>'Post Office');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $pc_exists = PostalCodeList::where('postal_code',trim($data['pcName']))->where('sub_district_id',trim($data['pcSubDistrict']))->where('is_deleted',0)->first();
            if(!empty($pc_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Postal Code already exists in Sub District', 'errors' => 'Postal Code already exists in Sub District'));
            }
            
            $insertArray = array('postal_code'=>trim($data['pcName']),'post_office'=>trim($data['pcPostOffice']),'sub_district_id'=>trim($data['pcSubDistrict']),'status'=>trim($data['pcStatus']));
       
            $postal_code = PostalCodeList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Postal Code added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editPostalCode(Request $request,$id){
        try{
            $data = $request->all();
            $pc_id = $id;
            
            $pc_data = PostalCodeList::where('id',$pc_id)->where('is_deleted',0)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $sub_district_data = SubDistrictList::where('id',$pc_data->sub_district_id)->where('is_deleted',0)->first();
            $district_data = DistrictList::where('id',$sub_district_data->district_id)->where('is_deleted',0)->first();
            $state_data = StateList::where('id',$district_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/postal_code_edit',array('pc_data'=>$pc_data,'country_list'=>$country_list,'states_list'=>$states_list,'state_data'=>$state_data,'sub_district_data'=>$sub_district_data,'district_data'=>$district_data,'title'=>'Edit Postal Code'));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditPostalCode(Request $request,$id){
        try{
            $data = $request->all();
            
            $pc_id = $id;
            $pc_data = PostalCodeList::where('id',$pc_id)->where('is_deleted',0)->first();
            
            $validationRules = array('pcName'=>'required','pcStatus'=>'required','pcCountry'=>'required','pcState'=>'required','pcDistrict'=>'required','pcSubDistrict'=>'required','pcPostOffice'=>'required');
            $attributes = array('pcName'=>'Postal Code Name','pcStatus'=>'Postal Code Status','pcCountry'=>'Country','pcState'=>'State','pcDistrict'=>'District','pcSubDistrict'=>'Sub District','pcPostOffice'=>'Post Office');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $pc_exists = PostalCodeList::where('postal_code',trim($data['pcName']))->where('sub_district_id',trim($data['pcSubDistrict']))->where('id','!=',$pc_id)->where('is_deleted',0)->first();
            
            if(!empty($pc_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Postal Code already exists in Sub District', 'errors' => 'Postal Code already exists in Sub District'));
            }
            
            $updateArray = array('postal_code'=>trim($data['pcName']),'post_office'=>trim($data['pcPostOffice']),'sub_district_id'=>trim($data['pcSubDistrict']),'status'=>trim($data['pcStatus']));
       
            PostalCodeList::where('id',$pc_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Postal Code updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listPoliticalParty(Request $request){
        try{
            $data = $request->all();
            
            $pp_list = PoliticalPartyList::where('political_party_list.is_deleted',0);
            
            if(isset($data['pp_name']) && !empty($data['pp_name'])){
                $pp_list = $pp_list->where('political_party_list.party_name','LIKE','%'.trim($data['pp_name']).'%');
            }
            
            $pp_list = $pp_list->select('political_party_list.*')        
            ->orderBy('political_party_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/political_party_list',array('pp_list'=>$pp_list,'title'=>'Political Party List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addPoliticalParty(Request $request){
        try{
            $data = $request->all();
            
            return view('admin/master_data/political_party_add',array('title'=>'Add Political Party'));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddPoliticalParty(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('ppName'=>'required','ppShortName'=>'required','ppStatus'=>'required','status'=>'required','ppSymbol'=>'required|image|mimes:jpeg,png,jpg,gif|max:3072',);
            $attributes = array('ppName'=>'Political Party Name','ppShortName'=>'Short Name','ppStatus'=>'Party Status','ppSymbol'=>'Party Symbol','status'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $pp_exists = PoliticalPartyList::where('party_name',trim($data['ppName']))->where('is_deleted',0)->first();
            if(!empty($pp_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Political Party already exists with this Name', 'errors' => 'Political Party already exists with this Name'));
            }
            
            $image_name = CommonHelper::uploadImage($request,$request->file('ppSymbol'),'images/pol_party_images');
            
            $insertArray = array('party_name'=>trim($data['ppName']),'party_short_name'=>trim($data['ppShortName']),'party_status'=>trim($data['ppStatus']),'status'=>trim($data['status']),'party_symbol'=>$image_name);
       
            $pp = PoliticalPartyList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Political Party added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editPoliticalParty(Request $request,$id){
        try{
            $data = $request->all();
            $pp_id = $id;
            
            $pp_data = PoliticalPartyList::where('id',$pp_id)->where('is_deleted',0)->first();
            return view('admin/master_data/political_party_edit',array('title'=>'Edit Political Party','pp_data'=>$pp_data));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditPoliticalParty(Request $request,$id){
        try{
            $data = $request->all();
            $pp_id = $id;
            $image_name = '';
            
            $pp_data = PoliticalPartyList::where('id',$pp_id)->where('is_deleted',0)->first();
            
            $validationRules = array('ppName'=>'required','ppShortName'=>'required','ppStatus'=>'required','status'=>'required','ppSymbol'=>'image|mimes:jpeg,png,jpg,gif|max:3072',);
            $attributes = array('ppName'=>'Political Party Name','ppShortName'=>'Short Name','ppStatus'=>'Party Status','ppSymbol'=>'Party Symbol','status'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $pp_exists = PoliticalPartyList::where('party_name',trim($data['ppName']))->where('id','!=',$pp_id)->where('is_deleted',0)->first();
            if(!empty($pp_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Political Party already exists with this Name', 'errors' => 'Political Party already exists with this Name'));
            }
            
            if(!empty($request->file('ppSymbol'))){
                $image_name = CommonHelper::uploadImage($request,$request->file('ppSymbol'),'images/pol_party_images');
            }
            
            $updateArray = array('party_name'=>trim($data['ppName']),'party_short_name'=>trim($data['ppShortName']),'party_status'=>trim($data['ppStatus']),'status'=>trim($data['status']));
            
            if(!empty($image_name)){
                $updateArray['party_symbol'] = $image_name;
            }
       
            PoliticalPartyList::where('id',$pp_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Political Party updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listLAConstituency(Request $request){
        try{
            $data = $request->all();
            
            $la_constituency_list = LegislativeAssemblyConstituency::join('state_list as s', 's.id', '=', 'legislative_assembly_constituency.state_id')
            ->where('legislative_assembly_constituency.is_deleted',0)
            ->where('s.is_deleted',0);
            
            if(isset($data['lac_name']) && !empty($data['lac_name'])){
                $la_constituency_list = $la_constituency_list->where('legislative_assembly_constituency.constituency_name','LIKE','%'.trim($data['lac_name']).'%');
            }
            
            $la_constituency_list = $la_constituency_list->select('legislative_assembly_constituency.*','s.state_name')        
            ->orderBy('legislative_assembly_constituency.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/la_constituency_list',array('la_constituency_list'=>$la_constituency_list,'title'=>'Legislative Assembly Constituency List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addLAConstituency(Request $request){
        try{
            $data = $request->all();
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/la_constituency_add',array('title'=>'Add Legislative Assembly Constituency','country_list'=>$country_list,'states_list'=>$states_list));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddLAConstituency(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('constituencyName'=>'required','constituencyCountry'=>'required','constituencyState'=>'required','constituencyDistrictList'=>'required','constituencyStatus'=>'required');
            $attributes = array('constituencyName'=>'Constituency Name','constituencyCountry'=>'Constituency Country','constituencyState'=>'Constituency State','constituencyDistrictList'=>'District List','constituencyStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $constituency_exists = LegislativeAssemblyConstituency::where('constituency_name',trim($data['constituencyName']))->where('state_id',trim($data['constituencyState']))->where('is_deleted',0)->first();
            if(!empty($constituency_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Constituency already exists in State', 'errors' => 'Constituency already exists in State'));
            }
            
            $dis_str = implode(',',$data['constituencyDistrictList']);
            $insertArray = array('constituency_name'=>trim($data['constituencyName']),'state_id'=>trim($data['constituencyState']),'district_list'=>$dis_str,'status'=>trim($data['constituencyStatus']));
       
            $constituency = LegislativeAssemblyConstituency::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Constituency added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editLAConstituency(Request $request,$id){
        try{
            $data = $request->all();
            $constituency_id = $id;
            
            $constituency_data = LegislativeAssemblyConstituency::where('id',$constituency_id)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $state_data = StateList::where('id',$constituency_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/la_constituency_edit',array('title'=>'Edit Legislative Assembly Constituency','country_list'=>$country_list,'states_list'=>$states_list,'constituency_data'=>$constituency_data,'state_data'=>$state_data));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditLAConstituency(Request $request,$id){
        try{
            $data = $request->all();
            $constituency_id = $id;
            
            $constituency_data = LegislativeAssemblyConstituency::where('id',$constituency_id)->first();
            $validationRules = array('constituencyName'=>'required','constituencyCountry'=>'required','constituencyState'=>'required','constituencyDistrictList'=>'required','constituencyStatus'=>'required');
            $attributes = array('constituencyName'=>'Constituency Name','constituencyCountry'=>'Constituency Country','constituencyState'=>'Constituency State','constituencyDistrictList'=>'District List','constituencyStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $constituency_exists = LegislativeAssemblyConstituency::where('constituency_name',trim($data['constituencyName']))->where('state_id',trim($data['constituencyState']))->where('id','!=',$constituency_id)->where('is_deleted',0)->first();
            if(!empty($constituency_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Constituency already exists in State', 'errors' => 'Constituency already exists in State'));
            }
            
            $dis_str = implode(',',$data['constituencyDistrictList']);
            $updateArray = array('constituency_name'=>trim($data['constituencyName']),'state_id'=>trim($data['constituencyState']),'district_list'=>$dis_str,'status'=>trim($data['constituencyStatus']));
       
            LegislativeAssemblyConstituency::where('id',$constituency_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Constituency updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listPAConstituency(Request $request){
        try{
            $data = $request->all();
            
            $pa_constituency_list = ParliamentaryConstituency::join('state_list as s', 's.id', '=', 'parliamentary_constituency.state_id')
            ->where('parliamentary_constituency.is_deleted',0)
            ->where('s.is_deleted',0);
            
            if(isset($data['pc_name']) && !empty($data['pc_name'])){
                $pa_constituency_list = $pa_constituency_list->where('parliamentary_constituency.constituency_name','LIKE','%'.trim($data['pc_name']).'%');
            }
            
            $pa_constituency_list = $pa_constituency_list->select('parliamentary_constituency.*','s.state_name')        
            ->orderBy('parliamentary_constituency.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/pa_constituency_list',array('pa_constituency_list'=>$pa_constituency_list,'title'=>'Parliamentary Constituency List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addPAConstituency(Request $request){
        try{
            $data = $request->all();
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/pa_constituency_add',array('title'=>'Add Parliamentary Constituency','country_list'=>$country_list,'states_list'=>$states_list));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddPAConstituency(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('constituencyName'=>'required','constituencyCountry'=>'required','constituencyState'=>'required','constituencyDistrictList'=>'required','constituencyStatus'=>'required');
            $attributes = array('constituencyName'=>'Constituency Name','constituencyCountry'=>'Constituency Country','constituencyState'=>'Constituency State','constituencyDistrictList'=>'District List','constituencyStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $constituency_exists = ParliamentaryConstituency::where('constituency_name',trim($data['constituencyName']))->where('state_id',trim($data['constituencyState']))->where('is_deleted',0)->first();
            if(!empty($constituency_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Constituency already exists in State', 'errors' => 'Constituency already exists in State'));
            }
            
            $dis_str = implode(',',$data['constituencyDistrictList']);
            $insertArray = array('constituency_name'=>trim($data['constituencyName']),'state_id'=>trim($data['constituencyState']),'district_list'=>$dis_str,'status'=>trim($data['constituencyStatus']));
       
            $constituency = ParliamentaryConstituency::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Constituency added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editPAConstituency(Request $request,$id){
        try{
            $data = $request->all();
            $constituency_id = $id;
            
            $constituency_data = ParliamentaryConstituency::where('id',$constituency_id)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $state_data = StateList::where('id',$constituency_data->state_id)->where('is_deleted',0)->first();
            
            return view('admin/master_data/pa_constituency_edit',array('title'=>'Edit Parliamentary Constituency','country_list'=>$country_list,'states_list'=>$states_list,'constituency_data'=>$constituency_data,'state_data'=>$state_data));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditPAConstituency(Request $request,$id){
        try{
            $data = $request->all();
            $constituency_id = $id;
            
            $constituency_data = ParliamentaryConstituency::where('id',$constituency_id)->first();
            $validationRules = array('constituencyName'=>'required','constituencyCountry'=>'required','constituencyState'=>'required','constituencyDistrictList'=>'required','constituencyStatus'=>'required');
            $attributes = array('constituencyName'=>'Constituency Name','constituencyCountry'=>'Constituency Country','constituencyState'=>'Constituency State','constituencyDistrictList'=>'District List','constituencyStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $constituency_exists = ParliamentaryConstituency::where('constituency_name',trim($data['constituencyName']))->where('state_id',trim($data['constituencyState']))->where('id','!=',$constituency_id)->where('is_deleted',0)->first();
            if(!empty($constituency_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Constituency already exists in State', 'errors' => 'Constituency already exists in State'));
            }
            
            $dis_str = implode(',',$data['constituencyDistrictList']);
            $updateArray = array('constituency_name'=>trim($data['constituencyName']),'state_id'=>trim($data['constituencyState']),'district_list'=>$dis_str,'status'=>trim($data['constituencyStatus']));
       
            ParliamentaryConstituency::where('id',$constituency_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Constituency updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    
    function listGovernmentDepartment(Request $request){
        try{
            $data = $request->all();
            
            $govt_dept_list = GovernmentDepartment::leftJoin('country_list as c', 'c.id', '=', 'government_department.country_id')
            ->leftJoin('state_list as s', 's.id', '=', 'government_department.state_id')
            ->where('government_department.is_deleted',0);
            
            if(isset($data['dep_name']) && !empty($data['dep_name'])){
                $govt_dept_list = $govt_dept_list->where('government_department.department_name','LIKE','%'.trim($data['dep_name']).'%');
            }
            
            $govt_dept_list = $govt_dept_list->select('government_department.*','s.state_name','c.country_name')        
            ->orderBy('government_department.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/government_department_list',array('govt_dept_list'=>$govt_dept_list,'title'=>'Government Department List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addGovernmentDepartment(Request $request){
        try{
            $data = $request->all();
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/government_department_add',array('title'=>'Add Government Department','country_list'=>$country_list,'states_list'=>$states_list));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddGovernmentDepartment(Request $request){
        try{
            $data = $request->all();
            $image_name = '';
            
            $validationRules = array('departmentName'=>'required','departmentShortName'=>'required','departmentStatus'=>'required','departmentType'=>'required','departmentIcon'=>'required|image|mimes:jpeg,png,jpg,gif|max:3072');
            $attributes = array('departmentName'=>'Department Name','departmentCountry'=>'Department Country','departmentState'=>'Department State','departmentShortName'=>'Short Name','departmentStatus'=>'Status','departmentType'=>'Department Type','departmentIcon'=>'Department Icon');
            
            if(isset($data['departmentType']) && !empty($data['departmentType'])){
                if($data['departmentType'] == 'national'){
                    $validationRules['departmentCountry'] = 'required';
                }elseif($data['departmentType'] == 'state'){
                    $validationRules['departmentCountry'] = 'required';
                    $validationRules['departmentState'] = 'required';
                }elseif($data['departmentType'] == 'other'){
                    $validationRules['departmentOtherTypeName'] = 'required';
                }
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $department_exists = GovernmentDepartment::where('department_name',trim($data['departmentName']))->where('country_id',trim($data['departmentCountry']))->where('is_deleted',0)->first();
            if(!empty($department_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Department already exists in Country', 'errors' => 'Department already exists in Country'));
            }
            
            if(!empty($request->file('departmentIcon'))){
                $image_name = CommonHelper::uploadImage($request,$request->file('departmentIcon'),'images/govt_dept_icon');
            }
            
            $dept_country = (isset($data['departmentCountry']) && !empty($data['departmentCountry']))?trim($data['departmentCountry']):null;
            $dept_state = (isset($data['departmentState']) && !empty($data['departmentState']))?trim($data['departmentState']):null;
            $insertArray = array('department_name'=>trim($data['departmentName']),'country_id'=>$dept_country,'state_id'=>$dept_state,'status'=>trim($data['departmentStatus']),'department_short_name'=>trim($data['departmentShortName']),'department_type'=>trim($data['departmentType']),'department_icon'=>$image_name,'other_type_name'=>trim($data['departmentOtherTypeName']));
       
            $department = GovernmentDepartment::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Department added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editGovernmentDepartment(Request $request,$id){
        try{
            $data = $request->all();
            $department_id = $id;
            
            $department_data = GovernmentDepartment::where('id',$department_id)->first();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/government_department_edit',array('title'=>'Edit Government Department','country_list'=>$country_list,'states_list'=>$states_list,'department_data'=>$department_data));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditGovernmentDepartment(Request $request,$id){
        try{
            $data = $request->all();
            $image_name = '';
            
            $department_id = $id;
            $department_data = GovernmentDepartment::where('id',$department_id)->first();
            
            $validationRules = array('departmentName'=>'required','departmentShortName'=>'required','departmentStatus'=>'required','departmentType'=>'required','departmentIcon'=>'image|mimes:jpeg,png,jpg,gif|max:3072');
            $attributes = array('departmentName'=>'Department Name','departmentCountry'=>'Department Country','departmentState'=>'Department State','departmentShortName'=>'Short Name','departmentStatus'=>'Status','departmentType'=>'Department Type','departmentIcon'=>'Department Icon');
            
            if(isset($data['departmentType']) && !empty($data['departmentType'])){
                if($data['departmentType'] == 'national'){
                    $validationRules['departmentCountry'] = 'required';
                }elseif($data['departmentType'] == 'state'){
                    $validationRules['departmentCountry'] = 'required';
                    $validationRules['departmentState'] = 'required';
                }elseif($data['departmentType'] == 'other'){
                    $validationRules['departmentOtherTypeName'] = 'required';
                }
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $department_exists = GovernmentDepartment::where('department_name',trim($data['departmentName']))->where('country_id',trim($data['departmentCountry']))->where('id','!=',$department_id)->where('is_deleted',0)->first();
            if(!empty($department_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Department already exists in Country', 'errors' => 'Department already exists in Country'));
            }
            
            if(!empty($request->file('departmentIcon'))){
                $image_name = CommonHelper::uploadImage($request,$request->file('departmentIcon'),'images/govt_dept_icon');
            }
            
            if($data['departmentType'] == 'national'){
                $dept_country = trim($data['departmentCountry']);
                $dept_state = $other_type_name = null;
            }
            
            if($data['departmentType'] == 'state'){
                $dept_country = $other_type_name = null;
                $dept_state = trim($data['departmentState']);;
            }
            
            if($data['departmentType'] == 'other'){
                $dept_country = $dept_state = null;
                $other_type_name = trim($data['departmentOtherTypeName']);
            }
            
            $updateArray = array('department_name'=>trim($data['departmentName']),'country_id'=>$dept_country,'state_id'=>$dept_state,'status'=>trim($data['departmentStatus']),'department_short_name'=>trim($data['departmentShortName']),'department_type'=>trim($data['departmentType']),'other_type_name'=>$other_type_name);
            
            if(!empty($image_name)){
                $updateArray['department_icon'] = $image_name;
            }
       
            GovernmentDepartment::where('id',$department_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Department updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listNonProfitOrganization(Request $request){
        try{
            $data = $request->all();
            
            $np_org_list = NonProfitOrganization::where('non_profit_organization.is_deleted',0);
            
            if(isset($data['org_name']) && !empty($data['org_name'])){
                $np_org_list = $np_org_list->where('non_profit_organization.organization_name','LIKE','%'.trim($data['org_name']).'%');
            }
            
            $np_org_list = $np_org_list->select('non_profit_organization.*')        
            ->orderBy('non_profit_organization.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/non_profit_organization_list',array('np_org_list'=>$np_org_list,'title'=>'Non Profit Organisation List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addNonProfitOrganization(Request $request){
        try{
            $data = $request->all();
            return view('admin/master_data/non_profit_organization_add',array('title'=>'Add Non Profit Organisation'));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddNonProfitOrganization(Request $request){
        try{
            $data = $request->all();
            $image_name = '';
            
            $validationRules = array('organizationName'=>'required','organizationShortName'=>'required','organizationStatus'=>'required','organizationType'=>'required','organizationIcon'=>'required|image|mimes:jpeg,png,jpg,gif|max:3072','organizationWorkingArea'=>'required');
            $attributes = array('organizationName'=>'Organization Name','organizationShortName'=>'Short Name','organizationStatus'=>'Status','organizationType'=>'Organization Type','organizationIcon'=>'Organization Icon','organizationWorkingArea'=>'Organization Working Area');
            
            if(isset($data['organizationType']) && $data['organizationType'] == 'other'){
                $validationRules['organizationOtherTypeName'] = 'required';
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $organization_exists = NonProfitOrganization::where('organization_name',trim($data['organizationName']))->where('is_deleted',0)->first();
            if(!empty($organization_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Organization already exists with this Name', 'errors' => 'Organization already exists with this Name'));
            }
            
            if(!empty($request->file('organizationIcon'))){
                $image_name = CommonHelper::uploadImage($request,$request->file('organizationIcon'),'images/non_profit_org_icon');
            }
            
            $insertArray = array('organization_name'=>trim($data['organizationName']),'status'=>trim($data['organizationStatus']),'organization_short_name'=>trim($data['organizationShortName']),'organization_type'=>trim($data['organizationType']),'organization_icon'=>$image_name,'other_type_name'=>trim($data['organizationOtherTypeName']),'working_area'=>trim($data['organizationWorkingArea']));
       
            $organization = NonProfitOrganization::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Organization added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editNonProfitOrganization(Request $request,$id){
        try{
            $data = $request->all();
            $organization_id = $id;
            
            $organization_data = NonProfitOrganization::where('id',$organization_id)->first();
            return view('admin/master_data/non_profit_organization_edit',array('title'=>'Edit Non Profit Organisation','organization_data'=>$organization_data));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditNonProfitOrganization(Request $request,$id){
        try{
            $data = $request->all();
            $image_name = '';
            $organization_id = $id;
            
            $organization_data = NonProfitOrganization::where('id',$organization_id)->first();
            
            $validationRules = array('organizationName'=>'required','organizationShortName'=>'required','organizationStatus'=>'required','organizationType'=>'required','organizationIcon'=>'image|mimes:jpeg,png,jpg,gif|max:3072','organizationWorkingArea'=>'required');
            $attributes = array('organizationName'=>'Organization Name','organizationShortName'=>'Short Name','organizationStatus'=>'Status','organizationType'=>'Organization Type','organizationIcon'=>'Organization Icon','organizationWorkingArea'=>'Organization Working Area');
            
            if(isset($data['organizationType']) && $data['organizationType'] == 'other'){
                $validationRules['organizationOtherTypeName'] = 'required';
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $organization_exists = NonProfitOrganization::where('organization_name',trim($data['organizationName']))->where('id','!=',$organization_id)->where('is_deleted',0)->first();
            if(!empty($organization_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Organization already exists with this Name', 'errors' => 'Organization already exists with this Name'));
            }
            
            $updateArray = array('organization_name'=>trim($data['organizationName']),'status'=>trim($data['organizationStatus']),'organization_short_name'=>trim($data['organizationShortName']),'organization_type'=>trim($data['organizationType']),'other_type_name'=>trim($data['organizationOtherTypeName']),'working_area'=>trim($data['organizationWorkingArea']));
            
            if(!empty($request->file('organizationIcon'))){
                $image_name = CommonHelper::uploadImage($request,$request->file('organizationIcon'),'images/non_profit_org_icon');
                $updateArray['organizationIcon'] = $image_name;
            }
       
            $organization = NonProfitOrganization::where('id',$organization_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Organization updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listElectedOfficialPosition(Request $request){
        try{
            $data = $request->all();
            
            $position_list = ElectedOfficialPosition::where('elected_official_position.is_deleted',0);
            
            if(isset($data['pos_name']) && !empty($data['pos_name'])){
                $position_list = $position_list->where('elected_official_position.position_name','LIKE','%'.trim($data['pos_name']).'%');
            }
            
            $position_list = $position_list->select('elected_official_position.*')        
            ->orderBy('elected_official_position.id','ASC')
            ->paginate(50);
            
            $rep_area = CommonHelper::getRepresentationAreaList();
            
            return view('admin/master_data/elected_official_position_list',array('position_list'=>$position_list,'title'=>'Elected Official Position List','rep_area'=>$rep_area));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addElectedOfficialPosition(Request $request){
        try{
            $data = $request->all();
            $rep_area = CommonHelper::getRepresentationAreaList();
            return view('admin/master_data/elected_official_position_add',array('title'=>'Add Elected Official Position','rep_area'=>$rep_area));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddElectedOfficialPosition(Request $request){
        try{
            $data = $request->all();
            $image_name = '';
            
            $validationRules = array('positionName'=>'required','positionRepresentationArea'=>'required','positionStatus'=>'required');
            $attributes = array('positionName'=>'Organization Name','positionRepresentationArea'=>'Representation Area','positionStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $position_exists = ElectedOfficialPosition::where('position_name',trim($data['positionName']))->where('is_deleted',0)->first();
            if(!empty($position_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Position already exists with this Name', 'errors' => 'Position already exists with this Name'));
            }
            
            $insertArray = array('position_name'=>trim($data['positionName']),'status'=>trim($data['positionStatus']),'representation_area'=>trim($data['positionRepresentationArea']));
       
            $position = ElectedOfficialPosition::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Position added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editElectedOfficialPosition(Request $request,$id){
        try{
            $data = $request->all();
            
            $position_id = $id;
            $position_data = ElectedOfficialPosition::where('id',$position_id)->first();
            $rep_area = CommonHelper::getRepresentationAreaList();
            return view('admin/master_data/elected_official_position_edit',array('title'=>'Edit Elected Official Position','position_data'=>$position_data,'rep_area'=>$rep_area));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditElectedOfficialPosition(Request $request,$id){
        try{
            $data = $request->all();
           
            $position_id = $id;
            $position_data = ElectedOfficialPosition::where('id',$position_id)->first();
            
            $validationRules = array('positionName'=>'required','positionRepresentationArea'=>'required','positionStatus'=>'required');
            $attributes = array('positionName'=>'Organization Name','positionRepresentationArea'=>'Representation Area','positionStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $position_exists = ElectedOfficialPosition::where('position_name',trim($data['positionName']))->where('id','!=',$position_id)->where('is_deleted',0)->first();
            if(!empty($position_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Position already exists with this Name', 'errors' => 'Position already exists with this Name'));
            }
            
            $updateArray = array('position_name'=>trim($data['positionName']),'status'=>trim($data['positionStatus']),'representation_area'=>trim($data['positionRepresentationArea']));
       
            ElectedOfficialPosition::where('id',$position_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Position updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    
    function listPoliticalPartyOfficialPosition(Request $request){
        try{
            $data = $request->all();
            
            $position_list = PoliticalPartyOfficialPosition::where('political_party_official_position.is_deleted',0);
            
            if(isset($data['pos_name']) && !empty($data['pos_name'])){
                $position_list = $position_list->where('political_party_official_position.position_name','LIKE','%'.trim($data['pos_name']).'%');
            }
            
            $position_list = $position_list->select('political_party_official_position.*')        
            ->orderBy('political_party_official_position.id','ASC')
            ->paginate(50);
            
            $rep_area = CommonHelper::getRepresentationAreaList();
            
            return view('admin/master_data/political_party_official_position_list',array('position_list'=>$position_list,'rep_area'=>$rep_area,'title'=>'Political Party Official Position List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addPoliticalPartyOfficialPosition(Request $request){
        try{
            $data = $request->all();
            $rep_area = CommonHelper::getRepresentationAreaList();
            
            return view('admin/master_data/political_party_official_position_add',array('title'=>'Add Political Party Official Position','rep_area'=>$rep_area));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddPoliticalPartyOfficialPosition(Request $request){
        try{
            $data = $request->all();
            $image_name = '';
            
            $validationRules = array('positionName'=>'required','positionRepresentationArea'=>'required','positionStatus'=>'required');
            $attributes = array('positionName'=>'Organization Name','positionRepresentationArea'=>'Representation Area','positionStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $position_exists = PoliticalPartyOfficialPosition::where('position_name',trim($data['positionName']))->where('is_deleted',0)->first();
            if(!empty($position_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Position already exists with this Name', 'errors' => 'Position already exists with this Name'));
            }
            
            $insertArray = array('position_name'=>trim($data['positionName']),'status'=>trim($data['positionStatus']),'representation_area'=>trim($data['positionRepresentationArea']));
       
            $position = PoliticalPartyOfficialPosition::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Position added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editPoliticalPartyOfficialPosition(Request $request,$id){
        try{
            $data = $request->all();
            
            $position_id = $id;
            $position_data = PoliticalPartyOfficialPosition::where('id',$position_id)->first();
            $rep_area = CommonHelper::getRepresentationAreaList();
            
            return view('admin/master_data/political_party_official_position_edit',array('title'=>'Edit Political Party Official Position','position_data'=>$position_data,'rep_area'=>$rep_area));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditPoliticalPartyOfficialPosition(Request $request,$id){
        try{
            $data = $request->all();
           
            $position_id = $id;
            $position_data = PoliticalPartyOfficialPosition::where('id',$position_id)->first();
            
            $validationRules = array('positionName'=>'required','positionRepresentationArea'=>'required','positionStatus'=>'required');
            $attributes = array('positionName'=>'Organization Name','positionRepresentationArea'=>'Representation Area','positionStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $position_exists = PoliticalPartyOfficialPosition::where('position_name',trim($data['positionName']))->where('id','!=',$position_id)->where('is_deleted',0)->first();
            if(!empty($position_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Position already exists with this Name', 'errors' => 'Position already exists with this Name'));
            }
            
            $updateArray = array('position_name'=>trim($data['positionName']),'status'=>trim($data['positionStatus']),'representation_area'=>trim($data['positionRepresentationArea']));
       
            PoliticalPartyOfficialPosition::where('id',$position_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Position updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listGroup(Request $request){
        try{
            $data = $request->all();
            
            $group_list = GroupList::where('group_list.is_deleted',0);
            
            if(isset($data['group_name']) && !empty($data['group_name'])){
                $group_list = $group_list->where('group_list.group_name','LIKE','%'.trim($data['group_name']).'%');
            }
            
            $group_list  = $group_list->select('group_list.*')        
            ->orderBy('group_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/group_list',array('group_list'=>$group_list,'title'=>'Group List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addGroup(Request $request){
        try{
            $data = $request->all();
            return view('admin/master_data/group_add',array('title'=>'Add Group'));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddGroup(Request $request){
        try{
            $data = $request->all();
            $image_name = '';
            
            $validationRules = array('groupName'=>'required','groupType'=>'required','groupSubType'=>'required','groupStatus'=>'required');
            $attributes = array('groupName'=>'Group Name','groupType'=>'Group Type','groupSubType'=>'Group Sub Type','groupStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $group_exists = GroupList::where('group_name',trim($data['groupName']))->where('is_deleted',0)->first();
            if(!empty($group_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Group already exists with this Name', 'errors' => 'Group already exists with this Name'));
            }
            
            $insertArray = array('group_name'=>trim($data['groupName']),'group_type'=>trim($data['groupType']),'group_sub_type'=>trim($data['groupSubType']),'status'=>trim($data['groupStatus']));
       
            $group = GroupList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Group added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editGroup(Request $request,$id){
        try{
            $data = $request->all();
            
            $group_id = $id;
            $group_data = GroupList::where('id',$group_id)->first();
            return view('admin/master_data/group_edit',array('title'=>'Edit Group','group_data'=>$group_data));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditGroup(Request $request,$id){
        try{
            $data = $request->all();
            
            $group_id = $id;
            $group_data = GroupList::where('id',$group_id)->first();
            
            $validationRules = array('groupName'=>'required','groupType'=>'required','groupSubType'=>'required','groupStatus'=>'required');
            $attributes = array('groupName'=>'Group Name','groupType'=>'Group Type','groupSubType'=>'Group Sub Type','groupStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $group_exists = GroupList::where('group_name',trim($data['groupName']))->where('id','!=',$group_id)->where('is_deleted',0)->first();
            if(!empty($group_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Group already exists with this Name', 'errors' => 'Group already exists with this Name'));
            }
            
            $updateArray = array('group_name'=>trim($data['groupName']),'group_type'=>trim($data['groupType']),'group_sub_type'=>trim($data['groupSubType']),'status'=>trim($data['groupStatus']));
       
            $position = GroupList::where('id',$group_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Group updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listSubGroup(Request $request){
        try{
            $data = $request->all();
            
            $sub_group_list = SubGroupList::join('group_list as g', 'g.id', '=', 'sub_group_list.group_id')
            ->where('sub_group_list.is_deleted',0)        
            ->where('g.is_deleted',0);
            
            if(isset($data['sub_group_name']) && !empty($data['sub_group_name'])){
                $sub_group_list = $sub_group_list->where('sub_group_list.sub_group_name','LIKE','%'.trim($data['sub_group_name']).'%');
            }
            
            $sub_group_list = $sub_group_list->select('sub_group_list.*','g.group_name')        
            ->orderBy('sub_group_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/sub_group_list',array('sub_group_list'=>$sub_group_list,'title'=>'Sub Group List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addSubGroup(Request $request){
        try{
            $data = $request->all();
            $group_list = GroupList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/sub_group_add',array('title'=>'Add Sub Group','group_list'=>$group_list));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddSubGroup(Request $request){
        try{
            $data = $request->all();
            $image_name = '';
            
            $validationRules = array('subGroupName'=>'required','groupId'=>'required','subGroupStatus'=>'required');
            $attributes = array('subGroupName'=>'Group Name','groupId'=>'Group','subGroupStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $sub_group_exists = SubGroupList::where('sub_group_name',trim($data['subGroupName']))->where('group_id',trim($data['groupId']))->where('is_deleted',0)->first();
            if(!empty($sub_group_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Sub Group already exists in this Group', 'errors' => 'Sub Group already exists in this Group'));
            }
            
            $insertArray = array('sub_group_name'=>trim($data['subGroupName']),'group_id'=>trim($data['groupId']),'status'=>trim($data['subGroupStatus']));
       
            $sub_group = SubGroupList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Sub Group added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editSubGroup(Request $request,$id){
        try{
            $data = $request->all();
            
            $sub_group_id = $id;
            $sub_group_data = SubGroupList::where('id',$sub_group_id)->first();
            $group_list = GroupList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/sub_group_edit',array('title'=>'Edit Sub Group','group_list'=>$group_list,'sub_group_data'=>$sub_group_data));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditSubGroup(Request $request,$id){
        try{
            $data = $request->all();
            
            $sub_group_id = $id;
            $sub_group_data = SubGroupList::where('id',$sub_group_id)->first();
            
            $validationRules = array('subGroupName'=>'required','groupId'=>'required','subGroupStatus'=>'required');
            $attributes = array('subGroupName'=>'Group Name','groupId'=>'Group','subGroupStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $sub_group_exists = SubGroupList::where('sub_group_name',trim($data['subGroupName']))->where('group_id',trim($data['groupId']))->where('id','!=',$sub_group_id)->where('is_deleted',0)->first();
            if(!empty($sub_group_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Sub Group already exists in this Group', 'errors' => 'Sub Group already exists in this Group'));
            }
            
            $updateArray = array('sub_group_name'=>trim($data['subGroupName']),'group_id'=>trim($data['groupId']),'status'=>trim($data['subGroupStatus']));
       
            SubGroupList::where('id',$sub_group_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Sub Group updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function getOfficeBelongsToData(Request $request,$subGroupId){
        try{
            $group_data = SubGroupList::join('group_list as g', 'g.id', '=', 'sub_group_list.group_id')
            ->where('sub_group_list.id',$subGroupId)        
            ->where('sub_group_list.is_deleted',0)
            ->where('g.is_deleted',0)        
            ->select('sub_group_list.*','g.group_name','g.group_type','g.group_sub_type')        
            ->first();
        
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Office Belongs to Data','group_data'=>$group_data),200);
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listSubmissionPurpose(Request $request){
        try{
            $data = $request->all();
            
            $sub_purpose_list = SubmissionPurposeList::join('group_list as g', 'g.id', '=', 'submission_purpose_list.group_id')
            ->where('submission_purpose_list.is_deleted',0)        
            ->where('g.is_deleted',0);
            
            if(isset($data['sub_purpose_name']) && !empty($data['sub_purpose_name'])){
                $sub_purpose_list = $sub_purpose_list->where('submission_purpose_list.submission_purpose','LIKE','%'.trim($data['sub_purpose_name']).'%');
            }
            
            $sub_purpose_list = $sub_purpose_list->select('submission_purpose_list.*','g.group_name')        
            ->orderBy('submission_purpose_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/submission_purpose_list',array('sub_purpose_list'=>$sub_purpose_list,'title'=>'Submission Purpose List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addSubmissionPurpose(Request $request){
        try{
            $data = $request->all();
            $group_list = GroupList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/submission_purpose_add',array('title'=>'Add Submission Purpose','group_list'=>$group_list));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddSubmissionPurpose(Request $request){
        try{
            $data = $request->all();
            $image_name = '';
            
            $validationRules = array('submissionPurpose'=>'required','groupId'=>'required','submissionPurposeStatus'=>'required');
            $attributes = array('submissionPurpose'=>'Submission Purpose','groupId'=>'Group','submissionPurposeStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $submission_purpose_exists = SubmissionPurposeList::where('submission_purpose',trim($data['submissionPurpose']))->where('group_id',trim($data['groupId']))->where('is_deleted',0)->first();
            if(!empty($submission_purpose_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Submission Purpose already exists in this Group', 'errors' => 'Submission Purpose already exists in this Group'));
            }
            
            $insertArray = array('submission_purpose'=>trim($data['submissionPurpose']),'group_id'=>trim($data['groupId']),'status'=>trim($data['submissionPurposeStatus']));
       
            $submission_purpose = SubmissionPurposeList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Purpose added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editSubmissionPurpose(Request $request,$id){
        try{
            $data = $request->all();
            
            $sub_purpose_id = $id;
            $sub_purpose_data = SubmissionPurposeList::where('id',$sub_purpose_id)->first();
            
            $group_list = GroupList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/submission_purpose_edit',array('title'=>'Edit Submission Purpose','group_list'=>$group_list,'sub_purpose_data'=>$sub_purpose_data));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditSubmissionPurpose(Request $request,$id){
        try{
            $data = $request->all();
            
            $sub_purpose_id = $id;
            $sub_purpose_data = SubmissionPurposeList::where('id',$sub_purpose_id)->first();
            
            $validationRules = array('submissionPurpose'=>'required','groupId'=>'required','submissionPurposeStatus'=>'required');
            $attributes = array('submissionPurpose'=>'Submission Purpose','groupId'=>'Group','submissionPurposeStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $submission_purpose_exists = SubmissionPurposeList::where('submission_purpose',trim($data['submissionPurpose']))->where('group_id',trim($data['groupId']))->where('id','!=',$sub_purpose_id)->where('is_deleted',0)->first();
            if(!empty($submission_purpose_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Submission Purpose already exists in this Group', 'errors' => 'Submission Purpose already exists in this Group'));
            }
            
            $updateArray = array('submission_purpose'=>trim($data['submissionPurpose']),'group_id'=>trim($data['groupId']),'status'=>trim($data['submissionPurposeStatus']));
       
            SubmissionPurposeList::where('id',$sub_purpose_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Purpose updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listSubmissionType(Request $request){
        try{
            $data = $request->all();
            
            $sub_type_list = SubmissionTypeList::join('group_list as g', 'g.id', '=', 'submission_type_list.group_id')
            ->where('submission_type_list.is_deleted',0)        
            ->where('g.is_deleted',0);
            
            if(isset($data['sub_type']) && !empty($data['sub_type'])){
                $sub_type_list = $sub_type_list->where('submission_type_list.submission_type','LIKE','%'.trim($data['sub_type']).'%');
            }
            
            $sub_type_list = $sub_type_list->select('submission_type_list.*','g.group_name')        
            ->orderBy('submission_type_list.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/submission_type_list',array('sub_type_list'=>$sub_type_list,'title'=>'Submission Type List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addSubmissionType(Request $request){
        try{
            $data = $request->all();
            $group_list = GroupList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/submission_type_add',array('title'=>'Add Submission Type','group_list'=>$group_list));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddSubmissionType(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('submissionType'=>'required','groupId'=>'required','submissionTypeStatus'=>'required');
            $attributes = array('submissionType'=>'Submission Type','groupId'=>'Group','submissionTypeStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $submission_type_exists = SubmissionTypeList::where('submission_type',trim($data['submissionType']))->where('group_id',trim($data['groupId']))->where('is_deleted',0)->first();
            if(!empty($submission_type_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Submission Type already exists in this Group', 'errors' => 'Submission Type already exists in this Group'));
            }
            
            $insertArray = array('submission_type'=>trim($data['submissionType']),'group_id'=>trim($data['groupId']),'status'=>trim($data['submissionTypeStatus']));
       
            $submission_type = SubmissionTypeList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Type added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editSubmissionType(Request $request,$id){
        try{
            $data = $request->all();
            
            $sub_type_id = $id;
            $sub_type_data = SubmissionTypeList::where('id',$sub_type_id)->first();
            
            $group_list = GroupList::where('is_deleted',0)->get()->toArray();
            
            return view('admin/master_data/submission_type_edit',array('title'=>'Edit Submission Type','group_list'=>$group_list,'sub_type_data'=>$sub_type_data));
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditSubmissionType(Request $request,$id){
        try{
            $data = $request->all();//print_r($data);exit;
            
            $sub_type_id = $id;
            $sub_type_data = SubmissionTypeList::where('id',$sub_type_id)->first();
            
            $validationRules = array('submissionType'=>'required','groupId'=>'required','submissionTypeStatus'=>'required');
            $attributes = array('submissionType'=>'Submission Type','groupId'=>'Group','submissionTypeStatus'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $submission_type_exists = SubmissionTypeList::where('submission_type',trim($data['submissionType']))->where('group_id',trim($data['groupId']))->where('id','!=',$sub_type_id)->where('is_deleted',0)->first();
            if(!empty($submission_type_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Submission Type already exists in this Group', 'errors' => 'Submission Type already exists in this Group'));
            }
            
            $updateArray = array('submission_type'=>trim($data['submissionType']),'group_id'=>trim($data['groupId']),'status'=>trim($data['submissionTypeStatus']));
       
            SubmissionTypeList::where('id',$sub_type_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Type updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listReviewLevel(Request $request){
        try{
            $data = $request->all();
            
            $review_level_list = ReviewLevel::where('review_level.is_deleted',0);
            
            if(isset($data['review_level']) && !empty($data['review_level'])){
                $review_level_list = $review_level_list->where('review_level.review_level','LIKE','%'.trim($data['review_level']).'%');
            }
            
            $review_level_list = $review_level_list->select('review_level.*')        
            ->orderBy('review_level.id','ASC')
            ->paginate(50);
            
            return view('admin/master_data/review_level_list',array('review_level_list'=>$review_level_list,'title'=>'Review Level List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addReviewLevel(Request $request){
        try{
            $data = $request->all();
            
            $review_level_count = ReviewLevel::where('is_deleted',0)->get()->count();
            
            if($review_level_count == 0){
                $positions = [1=>'At First Place'];
            }else{
                $positions = [1=>'At First Place'];
                for($i=1;$i<=$review_level_count;$i++){
                    $positions[] = 'After Place '.$i;
                }
                
                $end = $review_level_count+1;
                $positions[$end] = 'At Last Place';
            }
            
            return view('admin/master_data/review_level_add',array('title'=>'Add Review Level','positions'=>$positions));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddReviewLevel(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('reviewLevel'=>'required','designation'=>'required','position'=>'required','status'=>'required');
            $attributes = array('reviewLevel'=>'Review Level','designation'=>'Designation','position'=>'Position','status'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $review_level_exists = ReviewLevel::where('review_level',trim($data['reviewLevel']))->where('is_deleted',0)->first();
            if(!empty($review_level_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Review Level already exists', 'errors' => 'Review Level already exists'));
            }
            
            // Update existing positions start
            $position_data = ReviewLevel::where('position','>=',$data['position'])->select('id','position')->orderBy('position')->get()->toArray();
            if(!empty($position_data)){
                for($i=0;$i<count($position_data);$i++){
                    $updateArray = ['position'=>($position_data[$i]['position']+1)];
                    ReviewLevel::where('id',$position_data[$i]['id'])->update($updateArray);
                }
            }
            
            // Update existing positions end
            
            $insertArray = array('review_level'=>trim($data['reviewLevel']),'designation'=>trim($data['designation']),'position'=>trim($data['position']),'status'=>trim($data['status']));
       
            $review_level = ReviewLevel::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Review Level added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editReviewLevel(Request $request,$id){
        try{
            $data = $request->all();
            
            $review_level_id = $id;
            $review_level_data = ReviewLevel::where('id',$review_level_id)->first();
            
            $review_level_count = ReviewLevel::where('is_deleted',0)->get()->count();
            
            if($review_level_count == 0){
                $positions = [1=>'At First Place'];
            }else{
                $positions = [1=>'At First Place'];
                for($i=1;$i<$review_level_count;$i++){
                    $positions[] = 'After Place '.$i;
                }
                
                //$end = $review_level_count+1;
                //$positions[$end] = 'At Last Place';
            }
            
            return view('admin/master_data/review_level_edit',array('title'=>'Edit Review Level','review_level_data'=>$review_level_data,'positions'=>$positions));
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditReviewLevel(Request $request,$id){
        try{
            $data = $request->all();
            
            $review_level_id = $id;
            $review_level_data = ReviewLevel::where('id',$review_level_id)->first();
            
            $validationRules = array('reviewLevel'=>'required','designation'=>'required','position'=>'required','status'=>'required');
            $attributes = array('reviewLevel'=>'Review Level','designation'=>'Designation','position'=>'Position','status'=>'Status');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $review_level_exists = ReviewLevel::where('review_level',trim($data['reviewLevel']))->where('id','!=',$review_level_id)->where('is_deleted',0)->first();
            if(!empty($review_level_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Review Level already exists', 'errors' => 'Review Level already exists'));
            }
            
            // Update existing position start
            if($review_level_data->position != $data['position']){
                $position_data = ReviewLevel::where('position',$data['position'])->select('id','position')->first();
                if(!empty($position_data)){
                    $updateArray = ['position'=>$review_level_data->position];
                    ReviewLevel::where('id',$position_data->id)->update($updateArray);
                }
            }
            
            // Update existing position end
            
            $updateArray = array('review_level'=>trim($data['reviewLevel']),'designation'=>trim($data['designation']),'position'=>trim($data['position']),'status'=>trim($data['status']));
       
            $review_level = ReviewLevel::where('id',$review_level_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Review Level updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getPostalCodeData(Request $request,$postal_code){
        try{
            $data = $request->all();
            $country_list = $state_list = $district_list = $sub_district_list = $village_list = [];
                    
            $postal_code_data = PostalCodeList::join('sub_district_list as sd', 'sd.id', '=', 'postal_code_list.sub_district_id')
            ->join('district_list as d', 'd.id', '=', 'sd.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('postal_code_list.postal_code',$postal_code)        
            ->where('postal_code_list.is_deleted',0)
            ->where('sd.is_deleted',0)                
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0)           
            ->select('postal_code_list.*','s.id as state_id' ,'d.id as district_id','sd.id as sub_district_id')        
            ->first();
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $state_list = StateList::where('is_deleted',0)->get()->toArray();
            
            if(!empty($postal_code_data)){
                $district_list = DistrictList::where('state_id',$postal_code_data->state_id)->where('is_deleted',0)->get()->toArray();
                $sub_district_list = SubDistrictList::where('district_id',$postal_code_data->district_id)->where('is_deleted',0)->get()->toArray();
                $village_list = VillageList::where('sub_district_id',$postal_code_data->sub_district_id)->where('is_deleted',0)->get()->toArray();
            }
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Postal Code data','country_list'=>$country_list,
            'state_list'=>$state_list,'district_list'=>$district_list,'sub_district_list'=>$sub_district_list,'village_list'=>$village_list,'postal_code_data'=>$postal_code_data),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function updateBulkMasterData(Request $request){
        try{
            $data = $request->all();
            
            $rep_area_data = RepresentationAreaList::where('rep_area_key',$data['type'])->first();
            $rep_area_text = !empty($rep_area_data)?$rep_area_data->representation_area:ucwords(str_replace('_',' ',$data['type']));
                    
            $ids = explode(',',trim($data['ids']));
            if(empty($data['ids'])){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Please select '.$rep_area_text, 'errors' => 'Please select '.$rep_area_text));
            }
            
            if($data['action'] == 'delete'){
                $updateArray = ['is_deleted'=>1];
            }elseif($data['action'] == 'disable'){
                $updateArray = ['status'=>0];
            }elseif($data['action'] == 'enable'){
                $updateArray = ['status'=>1];
            }
            
            if($data['type'] == 'district'){
                DistrictList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'state'){
                StateList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'mc1'){
                Mc1List::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'mc2'){
                Mc2List::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'cc'){
                CityCouncil::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'block'){
                BlockList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'subDistrict'){
                SubDistrictList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'ward'){
                WardList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'village'){
                VillageList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'postal_Code'){
                PostalCodeList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'political_Party'){
                PoliticalPartyList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'LAC'){
                LegislativeAssemblyConstituency::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'PC'){
                ParliamentaryConstituency::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'elected_Official_Position'){
                ElectedOfficialPosition::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'political_Party_Official_Position'){
                PoliticalPartyOfficialPosition::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'government_Department'){
                GovernmentDepartment::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'non_Profit_Organization'){
                NonProfitOrganization::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'group'){
                GroupList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'sub_Group'){
                SubGroupList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'submission_Purpose'){
                SubmissionPurposeList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'submission_Type'){
                SubmissionTypeList::wherein('id',$ids)->update($updateArray);
            }elseif($data['type'] == 'review_Level'){
                ReviewLevel::wherein('id',$ids)->update($updateArray);
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => $rep_area_text.' updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
}
