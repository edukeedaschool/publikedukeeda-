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
use App\Helpers\CommonHelper;
use Validator;
use Illuminate\Validation\Rule;

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
            
            $content_type_header = $request->header('Content-Type');
            if(empty($content_type_header) || strtolower($content_type_header) != 'application/json' ){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Invalid Content-Type Header', 'errors' => 'Invalid Content-Type Header'),200);
            }

            $validateionRules = array('email'=>'required|email','password'=>'required');
            $attributes = array();
            
            $validator = Validator::make($data,$validateionRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Email and Password are Required Fields', 'errors' => $validator->errors()),200);
            }	
            
            $credentials = $request->only('email', 'password');

            if(Auth::attempt(['email' =>$data['email'], 'password' =>$data['password'],'user_role'=>[4], 'status'=>1,'is_deleted'=>0])) {
                // Fetch user details from email
                $user_data = User::where('email',trim($data['email']))->wherein('user_role',[4])->where('is_deleted',0)->where('status',1)->select('id','name','api_token','api_token_created_at')->first();
                
                if(!empty($user_data->api_token) && (time()-strtotime($user_data->api_token_created_at))/3600 <= 240){
                    $api_token = $user_data->api_token;
                }else{
                    $api_token = md5(uniqid($user_data->id, true));
                    $updateArray = array('api_token'=>$api_token,'api_token_created_at'=>date('Y/m/d H:i:s'));
                    User::where('id',$user_data->id)->update($updateArray);
                    $user_data = User::where('id',$user_data->id)->select('id','name','api_token','api_token_created_at')->first();
                }
                
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Authenticated','user_data'=>$user_data),200);
            }else{
                return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'fail','message' => 'Incorrect login credentials'),200);
            }
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage().', '.$e->getLine(),'message'=>'Error in Processing Request'),200);
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
    
    function getDistrictList(Request $request){
        try{ 
            
            $data = $request->all();
            $qs_str = '';
            
            $districts_list = DistrictList::join('state_list as s', 's.id', '=', 'district_list.state_id')
            ->where('district_list.is_deleted',0)
            ->where('district_list.status',1)                
            ->where('s.status',1)        
            ->where('s.is_deleted',0);        
            
            if(isset($data['state_id']) && !empty($data['state_id'])){
                $districts_list = $districts_list->where('district_list.state_id',trim($data['state_id']));
                $qs_str.='&state_id='.trim($data['state_id']);
            }
            
            $districts_list = $districts_list->select('district_list.*','s.state_name')        
            ->orderBy('district_list.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $districts_list = $this->updateAPIResponse($districts_list,$qs_str);
            
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
    
    function getSubDistrictList(Request $request){
        try{ 
            $data = $request->all();
            $qs_str = '';
            
            $sub_district_list = SubDistrictList::join('district_list as d', 'd.id', '=', 'sub_district_list.district_id')
            ->join('state_list as s', 's.id', '=', 'd.state_id')
            ->where('sub_district_list.is_deleted',0)
            ->where('sub_district_list.status',1)
            ->where('d.status',1)
            ->where('s.status',1)        
            ->where('d.is_deleted',0)        
            ->where('s.is_deleted',0);
            
            if(isset($data['district_id']) && !empty($data['district_id'])){
                $sub_district_list = $sub_district_list->where('sub_district_list.district_id',trim($data['district_id']));
                $qs_str.='&district_id='.trim($data['district_id']);
            }
            
            $sub_district_list = $sub_district_list->select('sub_district_list.*','s.state_name','d.district_name')        
            ->orderBy('sub_district_list.id','ASC')
            ->paginate($this->REC_PER_PAGE);
            
            $sub_district_list = $this->updateAPIResponse($sub_district_list,$qs_str);
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Sub District List','sub_district_list'=>$sub_district_list),200);
            
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
            ->paginate($this->REC_PER_PAGE);
            
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
            ->paginate($this->REC_PER_PAGE);
            
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
