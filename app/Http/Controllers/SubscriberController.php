<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StateList;
use App\Models\CountryList;
use App\Models\DistrictList;
use App\Models\GroupList;
use App\Models\SubGroupList;
use App\Models\PoliticalPartyList;
use App\Models\PoliticalPartyOfficialPosition;
use App\Models\ElectedOfficialPosition;
use App\Models\SubscriberList;
use App\Models\SubDistrictList;
use App\Models\VillageList;
use App\Models\SubscriberReview;
use App\Models\ReviewLevel;
use App\Models\ReviewRange;
use App\Models\User;
use App\Models\RepresentationAreaList;
use App\Models\ReviewOfficial;
use App\Helpers\CommonHelper;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SubscriberController extends Controller
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

    public function addSubscriber(Request $request){
        try{
            $data = $request->all();
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $sub_group_list = SubGroupList::where('is_deleted',0)->get()->toArray();
            $pol_party_list = PoliticalPartyList::where('is_deleted',0)->get()->toArray();
            $off_pos_pol_party = PoliticalPartyOfficialPosition::where('is_deleted',0)->get()->toArray();
            $elec_off_position = ElectedOfficialPosition::where('is_deleted',0)->get()->toArray();
            $rep_area = CommonHelper::getRepresentationAreaList();
            
            $params = ['country_list'=>$country_list,'sub_group_list'=>$sub_group_list,'pol_party_list'=>$pol_party_list,'title'=>'Add Subscriber','off_pos_pol_party'=>$off_pos_pol_party,
            'elec_off_position'=>$elec_off_position,'country_list'=>$country_list,'states_list'=>$states_list,'rep_area'=>$rep_area];
            
            return view('admin/subscriber/subscriber_add',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddSubscriber(Request $request){
        try{
            $data = $request->all();
            
            $validationRules = array('subscriberName'=>'required','officeBelongsTo'=>'required','emailAddress'=>'required|email','mobileNumber'=>'required','addressLine1'=>'required',
            'postalCode'=>'required','country'=>'required','state'=>'required','district'=>'required','subscriberImage'=>'required|image|mimes:jpeg,png,jpg,gif|max:3072',
            'subscriberStatus'=>'required','password'=>'required|min:6|max:100','userName'=>'required');
            
            $attributes = array('subscriberName'=>'Subscriber Name','officeBelongsTo'=>'Office Belongs To','emailAddress'=>'Email Address','mobileNumber'=>'Mobile Number',
            'addressLine1'=>'Address Line1','postalCode'=>'Postal Code','subscriberImage'=>'Subscriber Image','subscriberStatus'=>'Status','politicalParty'=>'Political Party',
            'politicalParty'=>'Political Party','subscriberGender'=>'Gender','subscriberDOB'=>'DOB','politicalPartyOfficialPosition'=>'Political Party Official Position',
            'repAreaOfficialPartyPosition'=>'Official Position','electedOfficialPositionName'=>'Position Name','repAreaElectedOfficialPosition'=>'Official Position','userName'=>'Username',
            'keyIdentity2'=>'Key Identity','organizationName'=>'Organization Name','authorizedPersonName'=>'Authorized Person Name','authorizedPersonDesignation'=>'Authorized Person Designation');
            
            $fields = ['country'=>'Country','state'=>'State','district'=>'District','LAC'=>'Legislative Assembly Constituency','PC'=>'Parliamentary Constituency',
            'MC1'=>'Municipal Corporation','MC2'=>'Municipality','CC'=>'City Council','block'=>'Block','ward'=>'Ward','subDistrict'=>'Sub District','village'=>'Village'];
            
            foreach($fields as $key=>$value){
                $attributes[$key.'_pp'] = $value;
                $attributes[$key.'_eo'] = $value;
            }
                    
            if(!empty($data['officeBelongsTo'])){
                $group_data = SubGroupList::join('group_list as g', 'g.id', '=', 'sub_group_list.group_id')
                ->where('sub_group_list.id',$data['officeBelongsTo'])        
                ->where('sub_group_list.is_deleted',0)
                ->where('g.is_deleted',0)        
                ->select('sub_group_list.*','g.group_name','g.group_type','g.group_sub_type')        
                ->first();
                
                if($group_data->group_type == 'political'){
                    $validationRules['politicalParty'] = 'required';
                }
                
                if($group_data->group_type == 'political' && $group_data->group_sub_type == 'person'){
                    $validationRules['subscriberGender'] = 'required';
                    $validationRules['subscriberDOB'] = 'required';
                    $validationRules['politicalParty'] = 'required';
                    $validationRules['politicalPartyOfficialPosition'] = 'required';
                    //$validationRules['repAreaOfficialPartyPosition'] = 'required';
                    $validationRules['electedOfficialPositionName'] = 'required';
                    //$validationRules['repAreaElectedOfficialPosition'] = 'required';
                    
                    if($group_data->group_sub_type !== null && $group_data->group_sub_type == 'person' && $data['politicalPartyOfficialPosition'] != "0"){
                        $validationRules['repAreaOfficialPartyPosition'] = 'required';
                    }
                    
                    if($group_data->group_sub_type !== null && $group_data->group_sub_type == 'person' && $data['electedOfficialPositionName'] != "0"){
                        $validationRules['repAreaElectedOfficialPosition'] = 'required';
                    }
                    
                    if(!empty($data['repAreaOfficialPartyPosition'])){
                        $loc_list = $this->getLocList();
                        $rep_area = trim($data['repAreaOfficialPartyPosition']);
                        $req_fields = explode(',',$loc_list[$rep_area]);
                        
                        for($i=0;$i<count($req_fields);$i++){
                            $field = $req_fields[$i].'_pp';
                            $validationRules[$field] = 'required';
                        }
                    }
                    
                    if(!empty($data['repAreaElectedOfficialPosition'])){
                        $loc_list = $this->getLocList();
                        $rep_area = trim($data['repAreaElectedOfficialPosition']);
                        $req_fields = explode(',',$loc_list[$rep_area]);
                        
                        for($i=0;$i<count($req_fields);$i++){
                            $field = $req_fields[$i].'_eo';
                            $validationRules[$field] = 'required';
                        }
                    }
                }
                
                if($group_data->group_type == 'non_political' && $group_data->group_sub_type == 'person'){
                    $validationRules['subscriberGender'] = 'required';$validationRules['subscriberDOB'] = 'required';
                    $validationRules['keyIdentity2'] = 'required'; $validationRules['organizationName'] = 'required';
                }
                
                if($group_data->group_sub_type == 'government_department' || $group_data->group_sub_type == 'nonprofit_organization'){
                    $validationRules['authorizedPersonName'] = 'required';$validationRules['authorizedPersonDesignation'] = 'required';
                }
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            //$sub_exists = SubscriberList::where('email_address',trim($data['emailAddress']))->where('is_deleted',0)->first();
            $sub_exists = User::where('email',trim($data['emailAddress']))->where('is_deleted',0)->first();
            if(!empty($sub_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Subscriber already exists with this Email Address', 'errors' => 'Subscriber already exists with this Email Address'));
            }
            
            $sub_exists = User::where('user_name',trim($data['userName']))->where('is_deleted',0)->first();
            if(!empty($sub_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Subscriber already exists with this Username', 'errors' => 'Subscriber already exists with this Username'));
            }
            
            \DB::beginTransaction();
            
            $image_name = CommonHelper::uploadImage($request,$request->file('subscriberImage'),'images/user_images');
            
            $village = !empty($data['village'])?$data['village']:null;
            
            $insertArray = array('name'=>trim($data['subscriberName']),'email'=>trim($data['emailAddress']),'mobile_no'=>trim($data['mobileNumber']),'address_line1'=>trim($data['addressLine1']),
            'postal_code'=>trim($data['postalCode']),'user_role'=>2,'country'=>trim($data['country']),'state'=>trim($data['state']),'district'=>trim($data['district']),'user_name'=>trim($data['userName']),
            'sub_district'=>trim($data['subDistrict']),'village'=>$village,'sub_district'=>trim($data['subDistrict']),'image'=>$image_name,'password'=>Hash::make($data['password']));
                
            $user_data = User::create($insertArray);
            
            /*$sub_exists = SubscriberList::where('mobile_no',trim($data['mobileNumber']))->where('is_deleted',0)->first();
            if(!empty($sub_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Subscriber already exists with this Mobile Number', 'errors' => 'Subscriber already exists with this Mobile Number'));
            }*/
            
            $insertArray = [];
            
            $fieldsArray = ['office_belongs_to'=>'officeBelongsTo','political_party'=>'politicalParty','gender'=>'subscriberGender','dob'=>'subscriberDOB',
            'off_pos_pol_party'=>'politicalPartyOfficialPosition','rep_area_off_party_pos'=>'repAreaOfficialPartyPosition','elec_off_pos_name'=>'electedOfficialPositionName',
            'rep_area_elec_off_pos'=>'repAreaElectedOfficialPosition','key_identity1'=>'keyIdentity1','key_identity2'=>'keyIdentity2','org_name'=>'organizationName',
            'auth_person_name'=>'authorizedPersonName','auth_person_designation'=>'authorizedPersonDesignation',
            'country_pp'=>'country_pp','state_pp'=>'state_pp','district_pp'=>'district_pp','lac_pp'=>'LAC_pp','pc_pp'=>'PC_pp','mc1_pp'=>'MC1_pp','mc2_pp'=>'MC2_pp',
            'cc_pp'=>'CC_pp','block_pp'=>'block_pp','ward_pp'=>'ward_pp','sub_district_pp'=>'subDistrict_pp','village_pp'=>'village_pp',
            'country_eo'=>'country_eo','state_eo'=>'state_eo','district_eo'=>'district_eo','lac_eo'=>'LAC_eo','pc_eo'=>'PC_eo','mc1_eo'=>'MC1_eo','mc2_eo'=>'MC2_eo',
            'cc_eo'=>'CC_eo','block_eo'=>'block_eo','ward_eo'=>'ward_eo','sub_district_eo'=>'subDistrict_eo','village_eo'=>'village_eo','status'=>'subscriberStatus','bio'=>'subscriberBio'];
            
            foreach($fieldsArray as $key=>$value){
                $insertArray[$key] = (isset($data[$value]) && $data[$value] != '')?trim($data[$value]):null;
            }
            
            if($data['politicalPartyOfficialPosition'] == '0'){
                $insertArray['rep_area_off_party_pos'] = $insertArray['country_pp'] = $insertArray['state_pp'] = $insertArray['district_pp'] = $insertArray['lac_pp'] = $insertArray['pc_pp'] = null;
                $insertArray['mc1_pp'] = $insertArray['mc2_pp'] = $insertArray['cc_pp'] = $insertArray['block_pp'] = $insertArray['ward_pp'] = $insertArray['sub_district_pp'] = $insertArray['village_pp'] = null;
            }
            
            if($data['electedOfficialPositionName'] == '0'){
                $insertArray['rep_area_elec_off_pos'] = $insertArray['country_eo'] = $insertArray['state_eo'] = $insertArray['district_eo'] = $insertArray['lac_eo'] = $insertArray['pc_eo'] = null;
                $insertArray['mc1_eo'] = $insertArray['mc2_eo'] = $insertArray['cc_eo'] = $insertArray['block_eo'] = $insertArray['ward_eo'] = $insertArray['sub_district_eo'] = $insertArray['village_eo'] = null;
            }
            
            $insertArray['user_id'] = $user_data->id;
            
            $subscriber = SubscriberList::create($insertArray);
            
            User::where('id',$user_data->id)->update(['subscriber_id'=>$subscriber->id]);
          
            \DB::commit();
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscriber added successfully'),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage().', '.$e->getLine()),500);
        }  
    }
    
    function getLocList(){
        $loc_list = [];
        $loc_list['1'] = 'country'; $loc_list['2'] = 'country,state'; $loc_list['3'] = 'country,state,district';
        $loc_list['4'] = 'country,state,district,LAC';$loc_list['5'] = 'country,state,district,PC';
        $loc_list['6'] = 'country,state,MC1';$loc_list['7'] = 'country,state,district,MC2';$loc_list['8'] = 'country,state,district,CC';
        $loc_list['9'] = 'country,state,district,block';$loc_list['10'] = 'country,state,district,CC,ward';
        $loc_list['11'] = 'country,state,district,subDistrict'; $loc_list['12'] = 'country,state,district,subDistrict,village';

        return $loc_list;
    }
    
    public function editSubscriber(Request $request,$id){
        try{
            $data = $request->all();
            $subscriber_id = $id;
            $rep_area_pp_state_def_val = $rep_area_pp_district_def_val = $rep_area_eo_state_def_val = $rep_area_eo_district_def_val = '';
            
            $subscriber_data = SubscriberList::where('id',$subscriber_id)->first();
            $user_data = User::where('id',$subscriber_data->user_id)->where('is_deleted',0)->first();
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            $district_list = DistrictList::where('state_id',$user_data->state)->where('is_deleted',0)->get()->toArray();
            $sub_district_list = SubDistrictList::where('district_id',$user_data->district)->where('is_deleted',0)->get()->toArray();
            $village_list = VillageList::where('sub_district_id',$user_data->sub_district)->where('is_deleted',0)->get()->toArray();
                
            $sub_group_list = SubGroupList::where('is_deleted',0)->get()->toArray();
            $pol_party_list = PoliticalPartyList::where('is_deleted',0)->get()->toArray();
            $off_pos_pol_party = PoliticalPartyOfficialPosition::where('is_deleted',0)->get()->toArray();
            $elec_off_position = ElectedOfficialPosition::where('is_deleted',0)->get()->toArray();
            $rep_area = CommonHelper::getRepresentationAreaList();
            
            $rep_area_pp = $subscriber_data->rep_area_off_party_pos;
            
            /*if($rep_area_pp == 'district'){
                $rep_area_pp_state_def_val = $subscriber_data->district_pp;
            }
            if($rep_area_pp == 'municipal_corporation'){
                $rep_area_pp_state_def_val = $subscriber_data->mc1_pp;
            }*/
            
            if($rep_area_pp == '11'){
                $rep_area_pp_district_def_val = $subscriber_data->sub_district_pp;
            }elseif($rep_area_pp == '6'){
                $rep_area_pp_district_def_val = $subscriber_data->mc1_pp;
            }elseif($rep_area_pp == '12'){
                $rep_area_pp_district_def_val = $subscriber_data->village_pp;
            }elseif($rep_area_pp == '7'){
                $rep_area_pp_district_def_val = $subscriber_data->mc2_pp;
            }elseif($rep_area_pp == '8'){
                $rep_area_pp_district_def_val = $subscriber_data->cc_pp;
            }elseif($rep_area_pp == '10'){
                $rep_area_pp_district_def_val = $subscriber_data->ward_pp;
            }elseif($rep_area_pp == '9'){
                $rep_area_pp_district_def_val = $subscriber_data->block_pp;
            }elseif($rep_area_pp == '4'){
                $rep_area_pp_district_def_val = $subscriber_data->lac_pp;
            }elseif($rep_area_pp == '5'){
                $rep_area_pp_district_def_val = $subscriber_data->pc_pp;
            }
            
            
            $rep_area_eo = $subscriber_data->rep_area_elec_off_pos;
            
            /*if($rep_area_eo == 'district'){
                $rep_area_eo_state_def_val = $subscriber_data->district_eo;
            }
            if($rep_area_eo == 'municipal_corporation'){
                $rep_area_eo_state_def_val = $subscriber_data->mc1_eo;
            }*/
            
            if($rep_area_eo == '11'){
                $rep_area_eo_district_def_val = $subscriber_data->sub_district_eo;
            }elseif($rep_area_eo == '6'){
                $rep_area_eo_district_def_val = $subscriber_data->mc1_eo;
            }elseif($rep_area_eo == '12'){
                $rep_area_eo_district_def_val = $subscriber_data->village_eo;
            }elseif($rep_area_eo == '7'){
                $rep_area_eo_district_def_val = $subscriber_data->mc2_eo;
            }elseif($rep_area_eo == '8'){
                $rep_area_eo_district_def_val = $subscriber_data->cc_eo;
            }elseif($rep_area_eo == '10'){
                $rep_area_eo_district_def_val = $subscriber_data->ward_eo;
            }elseif($rep_area_eo == '9'){
                $rep_area_eo_district_def_val = $subscriber_data->block_eo;
            }elseif($rep_area_eo == '4'){
                $rep_area_eo_district_def_val = $subscriber_data->lac_eo;
            }elseif($rep_area_eo == '5'){
                $rep_area_eo_district_def_val = $subscriber_data->pc_eo;
            }
            
            $group_data = SubGroupList::join('group_list as g', 'g.id', '=', 'sub_group_list.group_id')
            ->where('sub_group_list.id',$subscriber_data->office_belongs_to)        
            ->where('sub_group_list.is_deleted',0)
            ->where('g.is_deleted',0)        
            ->select('sub_group_list.*','g.group_name','g.group_type','g.group_sub_type')        
            ->first();
            
            $params = ['country_list'=>$country_list,'sub_group_list'=>$sub_group_list,'pol_party_list'=>$pol_party_list,'title'=>'Edit Subscriber','off_pos_pol_party'=>$off_pos_pol_party,
            'elec_off_position'=>$elec_off_position,'country_list'=>$country_list,'states_list'=>$states_list,'rep_area'=>$rep_area,'subscriber_data'=>$subscriber_data,'district_list'=>$district_list,
            'sub_district_list'=>$sub_district_list,'village_list'=>$village_list,'rep_area_pp_state_def_val'=>$rep_area_pp_state_def_val,'rep_area_pp_district_def_val'=>$rep_area_pp_district_def_val,
            'rep_area_eo_state_def_val'=>$rep_area_eo_state_def_val,'rep_area_eo_district_def_val'=>$rep_area_eo_district_def_val,'group_data'=>$group_data,'user_data'=>$user_data];
            
            return view('admin/subscriber/subscriber_edit',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitEditSubscriber(Request $request,$id){
        try{
            $data = $request->all();
            
            $subscriber_id = $id;
            $subscriber_data = SubscriberList::where('id',$subscriber_id)->first();
            
            $validationRules = array('subscriberName'=>'required','officeBelongsTo'=>'required','emailAddress'=>'required|email','mobileNumber'=>'required','addressLine1'=>'required',
            'postalCode'=>'required','country'=>'required','state'=>'required','district'=>'required','subscriberImage'=>'image|mimes:jpeg,png,jpg,gif|max:3072','subscriberStatus'=>'required');
            
            $attributes = array('subscriberName'=>'Subscriber Name','officeBelongsTo'=>'Office Belongs To','emailAddress'=>'Email Address','mobileNumber'=>'Mobile Number',
            'addressLine1'=>'Address Line1','postalCode'=>'Postal Code','subscriberImage'=>'Subscriber Image','subscriberStatus'=>'Status','politicalParty'=>'Political Party',
            'politicalParty'=>'Political Party','subscriberGender'=>'Gender','subscriberDOB'=>'DOB','politicalPartyOfficialPosition'=>'Political Party Official Position',
            'repAreaOfficialPartyPosition'=>'Official Position','electedOfficialPositionName'=>'Position Name','repAreaElectedOfficialPosition'=>'Official Position',
            'keyIdentity2'=>'Key Identity','organizationName'=>'Organization Name','authorizedPersonName'=>'Authorized Person Name','authorizedPersonDesignation'=>'Authorized Person Designation');
            
            $fields = ['country'=>'Country','state'=>'State','district'=>'District','LAC'=>'Legislative Assembly Constituency','PC'=>'Parliamentary Constituency',
            'MC1'=>'Municipal Corporation','MC2'=>'Municipality','CC'=>'City Council','block'=>'Block','ward'=>'Ward','subDistrict'=>'Sub District','village'=>'Village'];
            
            foreach($fields as $key=>$value){
                $attributes[$key.'_pp'] = $value;
                $attributes[$key.'_eo'] = $value;
            }
                    
            if(!empty($data['officeBelongsTo'])){
                $group_data = SubGroupList::join('group_list as g', 'g.id', '=', 'sub_group_list.group_id')
                ->where('sub_group_list.id',$data['officeBelongsTo'])        
                ->where('sub_group_list.is_deleted',0)
                ->where('g.is_deleted',0)        
                ->select('sub_group_list.*','g.group_name','g.group_type','g.group_sub_type')        
                ->first();
                
                if($group_data->group_type == 'political'){
                    $validationRules['politicalParty'] = 'required';
                }
                
                if($group_data->group_type == 'political' && $group_data->group_sub_type == 'person'){
                    $validationRules['subscriberGender'] = 'required';
                    $validationRules['subscriberDOB'] = 'required';
                    $validationRules['politicalParty'] = 'required';
                    $validationRules['politicalPartyOfficialPosition'] = 'required';
                    //$validationRules['repAreaOfficialPartyPosition'] = 'required';
                    $validationRules['electedOfficialPositionName'] = 'required';
                    //$validationRules['repAreaElectedOfficialPosition'] = 'required';
                    
                    if($group_data->group_sub_type !== null && $group_data->group_sub_type == 'person' && $data['politicalPartyOfficialPosition'] != "0"){
                        $validationRules['repAreaOfficialPartyPosition'] = 'required';
                    }
                    
                    if($group_data->group_sub_type !== null && $group_data->group_sub_type == 'person' && $data['electedOfficialPositionName'] != "0"){
                        $validationRules['repAreaElectedOfficialPosition'] = 'required';
                    }
                    
                    if(!empty($data['repAreaOfficialPartyPosition'])){
                        $loc_list = $this->getLocList();
                        $rep_area = trim($data['repAreaOfficialPartyPosition']);
                        $req_fields = explode(',',$loc_list[$rep_area]);
                        
                        for($i=0;$i<count($req_fields);$i++){
                            $field = $req_fields[$i].'_pp';
                            $validationRules[$field] = 'required';
                        }
                    }
                    
                    if(!empty($data['repAreaElectedOfficialPosition'])){
                        $loc_list = $this->getLocList();
                        $rep_area = trim($data['repAreaElectedOfficialPosition']);
                        $req_fields = explode(',',$loc_list[$rep_area]);
                        
                        for($i=0;$i<count($req_fields);$i++){
                            $field = $req_fields[$i].'_eo';
                            $validationRules[$field] = 'required';
                        }
                    }
                }
                
                if($group_data->group_type == 'non_political' && $group_data->group_sub_type == 'person'){
                    $validationRules['subscriberGender'] = 'required';$validationRules['subscriberDOB'] = 'required';
                    $validationRules['keyIdentity2'] = 'required'; $validationRules['organizationName'] = 'required';
                }
                
                if($group_data->group_sub_type == 'government_department' || $group_data->group_sub_type == 'nonprofit_organization'){
                    $validationRules['authorizedPersonName'] = 'required';$validationRules['authorizedPersonDesignation'] = 'required';
                }
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            /*$sub_exists = SubscriberList::where('email_address',trim($data['emailAddress']))->where('id','!=',$subscriber_id)->where('is_deleted',0)->first();
            if(!empty($sub_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Subscriber already exists with this Email Address', 'errors' => 'Subscriber already exists with this Email Address'));
            }
            
            $sub_exists = SubscriberList::where('mobile_no',trim($data['mobileNumber']))->where('id','!=',$subscriber_id)->where('is_deleted',0)->first();
            if(!empty($sub_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Subscriber already exists with this Mobile Number', 'errors' => 'Subscriber already exists with this Mobile Number'));
            }*/
            
            $fieldsArray = ['office_belongs_to'=>'officeBelongsTo','political_party'=>'politicalParty','gender'=>'subscriberGender','dob'=>'subscriberDOB',
            'off_pos_pol_party'=>'politicalPartyOfficialPosition','rep_area_off_party_pos'=>'repAreaOfficialPartyPosition','elec_off_pos_name'=>'electedOfficialPositionName',
            'rep_area_elec_off_pos'=>'repAreaElectedOfficialPosition','key_identity1'=>'keyIdentity1','key_identity2'=>'keyIdentity2','org_name'=>'organizationName',
            'auth_person_name'=>'authorizedPersonName','auth_person_designation'=>'authorizedPersonDesignation',
            'country_pp'=>'country_pp','state_pp'=>'state_pp','district_pp'=>'district_pp','lac_pp'=>'LAC_pp','pc_pp'=>'PC_pp','mc1_pp'=>'MC1_pp','mc2_pp'=>'MC2_pp',
            'cc_pp'=>'CC_pp','block_pp'=>'block_pp','ward_pp'=>'ward_pp','sub_district_pp'=>'subDistrict_pp','village_pp'=>'village_pp',
            'country_eo'=>'country_eo','state_eo'=>'state_eo','district_eo'=>'district_eo','lac_eo'=>'LAC_eo','pc_eo'=>'PC_eo','mc1_eo'=>'MC1_eo','mc2_eo'=>'MC2_eo',
            'cc_eo'=>'CC_eo','block_eo'=>'block_eo','ward_eo'=>'ward_eo','sub_district_eo'=>'subDistrict_eo','village_eo'=>'village_eo','status'=>'subscriberStatus','bio'=>'subscriberBio'];
            
            foreach($fieldsArray as $key=>$value){
                $updateArray[$key] = (isset($data[$value]) && $data[$value] != '' )?trim($data[$value]):null;
            }
            
            if($data['politicalPartyOfficialPosition'] == '0'){
                $updateArray['rep_area_off_party_pos'] = $updateArray['country_pp'] = $updateArray['state_pp'] = $updateArray['district_pp'] = $updateArray['lac_pp'] = $updateArray['pc_pp'] = null;
                $updateArray['mc1_pp'] = $updateArray['mc2_pp'] = $updateArray['cc_pp'] = $updateArray['block_pp'] = $updateArray['ward_pp'] = $updateArray['sub_district_pp'] = $updateArray['village_pp'] = null;
            }
            
            if($data['electedOfficialPositionName'] == '0'){
                $updateArray['rep_area_elec_off_pos'] = $updateArray['country_eo'] = $updateArray['state_eo'] = $updateArray['district_eo'] = $updateArray['lac_eo'] = $updateArray['pc_eo'] = null;
                $updateArray['mc1_eo'] = $updateArray['mc2_eo'] = $updateArray['cc_eo'] = $updateArray['block_eo'] = $updateArray['ward_eo'] = $updateArray['sub_district_eo'] = $updateArray['village_eo'] = null;
            }
            
            $subscriber = SubscriberList::where('id',$subscriber_id)->update($updateArray);
            
            $village = !empty($data['village'])?$data['village']:null;
            
            $updateArray = array('name'=>trim($data['subscriberName']),'mobile_no'=>trim($data['mobileNumber']),'address_line1'=>trim($data['addressLine1']),
            'postal_code'=>trim($data['postalCode']),'country'=>trim($data['country']),'state'=>trim($data['state']),'district'=>trim($data['district']),
            'sub_district'=>trim($data['subDistrict']),'village'=>$village,'sub_district'=>trim($data['subDistrict']));
            
            if(!empty($request->file('subscriberImage'))){
                $image_name = CommonHelper::uploadImage($request,$request->file('subscriberImage'),'images/user_images');
                $updateArray['image'] = $image_name;
            }
                
            User::where('id',$subscriber_data->user_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscriber updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listSubscriber(Request $request){
        try{
            $data = $request->all();
            
            $subscriber_list = SubscriberList::join('sub_group_list as sg', 'sg.id', '=', 'subscriber_list.office_belongs_to')
            ->join('users as u1', 'u1.id', '=', 'subscriber_list.user_id')          
            ->join('country_list as c', 'c.id', '=', 'u1.country')        
            ->join('state_list as s', 's.id', '=', 'u1.state')        
            ->join('district_list as d', 'd.id', '=', 'u1.district')                
            ->where('subscriber_list.is_deleted',0)        
            ->where('sg.is_deleted',0);
            
            if(isset($data['sub_name']) && !empty($data['sub_name'])){
                $subscriber_list = $subscriber_list->where('subscriber_list.subscriber_name','LIKE','%'.trim($data['sub_name']).'%');
            }
            
            $subscriber_list = $subscriber_list->select('subscriber_list.*','sg.sub_group_name','c.country_name','s.state_name','d.district_name','u1.name as subscriber_name')        
            ->orderBy('subscriber_list.id','ASC')
            ->paginate(50);
            
            return view('admin/subscriber/subscriber_list',array('subscriber_list'=>$subscriber_list,'title'=>'Subscriber List'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    function editSubscriberReviewData(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            $subscriber_data = CommonHelper::getSubscriberData($user->id);
            $review_levels = $subscriber_reviews = $subscriber_reviews_list = [];
            
            $subscriber_reviews = SubscriberReview::join('review_level as rl', 'rl.id', '=', 'subscriber_review.review_level_id')
            ->join('review_range as rr', 'rr.id', '=', 'subscriber_review.review_range_id')        
            ->where('subscriber_review.subscriber_id',$subscriber_data->id)
            ->where('subscriber_review.is_deleted',0)        
            ->where('rl.is_deleted',0)                
            ->where('subscriber_review.status',1)                        
            ->where('rl.status',1)                          
            ->select('subscriber_review.*','rl.review_level','rl.designation','rl.position','rr.review_range')         
            ->get()->toArray();
            
            for($i=0;$i<count($subscriber_reviews);$i++){
                $id = $subscriber_reviews[$i]['review_level_id'];
                $subscriber_reviews_list[$id] = $subscriber_reviews[$i];
            }
            
            $review_levels = ReviewLevel::where('is_deleted',0)->get()->toArray();
            
            $review_ranges = ReviewRange::where('is_deleted',0)->where('status',1)->orderBy('priority','DESC')->get()->toArray();  
            
            return view('admin/subscriber/review_data_edit',array('subscriber_reviews'=>$subscriber_reviews,'review_levels'=>$review_levels,'sub_rev_list'=>$subscriber_reviews_list,
            'review_ranges'=>$review_ranges,'title'=>'Edit Subscriber Review Data'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    function getReviewRangeData(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $review_range_id = $id;
            $review_range_data = ReviewRange::where('id',$review_range_id)->where('is_deleted',0)->first();
            
            $review_range_list = ReviewRange::where('priority','<',$review_range_data->priority)->where('is_deleted',0)->where('status',1)->orderBy('priority','DESC')->get()->toArray();
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Review Range','review_range_list'=>$review_range_list,'review_range_data'=>$review_range_data),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function submitEditSubscriberReviewData(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            $review_range_added = false;
            $subscriber_data = CommonHelper::getSubscriberData($user->id);
            $subscriber_id = $subscriber_data->id;
            
            \DB::beginTransaction();
            
            $review_levels = ReviewLevel::where('is_deleted',0)->get()->toArray();
            for($i=0;$i<count($review_levels);$i++){
                $id = $review_levels[$i]['id'];
                if(isset($data['review_range_'.$id]) && !empty($data['review_range_'.$id]) ){
                    $review_range_added = true;
                    break;
                }
            }
            
            if(!$review_range_added){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Review Range is Required Field', 'errors' => 'Review Range is Required Field'));
            }
             
            $updateArray = ['is_deleted'=>1];
            SubscriberReview::where('subscriber_id',$subscriber_id)->update($updateArray);
            
            for($i=0;$i<count($review_levels);$i++){
                $id = $review_levels[$i]['id'];
                if(isset($data['review_range_'.$id]) && !empty($data['review_range_'.$id]) ){
                    $insertArray = ['subscriber_id'=>$subscriber_id,'review_level_id'=>$id,'review_range_id'=>$data['review_range_'.$id]];
                    SubscriberReview::create($insertArray);
                }
            }
            
            \DB::commit();
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Review Data updated successfully'),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function viewSubscriberReviewData(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            $review_levels = $subscriber_reviews = $subscriber_reviews_list = [];
            $subscriber_data = CommonHelper::getSubscriberData($user->id);
            
            $subscriber_reviews = SubscriberReview::join('review_level as rl', 'rl.id', '=', 'subscriber_review.review_level_id')
            ->join('review_range as rr', 'rr.id', '=', 'subscriber_review.review_range_id')        
            ->where('subscriber_review.subscriber_id',$subscriber_data->id)
            ->where('subscriber_review.is_deleted',0)        
            ->where('rl.is_deleted',0)                
            ->where('subscriber_review.status',1)                        
            ->where('rl.status',1)                          
            ->select('subscriber_review.*','rl.review_level','rl.designation','rl.position','rr.review_range')         
            ->get()->toArray();
            
            return view('admin/subscriber/review_data_view',array('subscriber_reviews'=>$subscriber_reviews,'title'=>'View Subscriber Review Data'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function updateSubscriber(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $ids = explode(',',trim($data['ids']));
            if(empty($data['ids'])){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Please select Subscribers', 'errors' => 'Please select Subscribers'));
            }
            
            if($data['action'] == 'delete'){
                SubscriberList::wherein('id',$ids)->update(['is_deleted'=>1]);
            }
            
            if($data['action'] == 'disable'){
                SubscriberList::wherein('id',$ids)->update(['status'=>0]);
            }
            
            if($data['action'] == 'enable'){
                SubscriberList::wherein('id',$ids)->update(['status'=>1]);
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscribers updated successfully'),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getSubscriberReviewData(Request $request,$Id){
        try{
            $data = $request->all();
            $user = Auth::user();
            $subscriber_data = CommonHelper::getSubscriberData($user->id);
            
            $subscriber_review_data = SubscriberReview::join('review_level as rl', 'rl.id', '=', 'subscriber_review.review_level_id')
            ->join('review_range as rr', 'rr.id', '=', 'subscriber_review.review_range_id')        
            ->where('subscriber_review.subscriber_id',$subscriber_data->id)
            ->where('subscriber_review.id',$Id)        
            ->where('subscriber_review.is_deleted',0)        
            ->where('rl.is_deleted',0)                
            ->where('rr.is_deleted',0)                      
            ->where('subscriber_review.status',1)                        
            ->where('rl.status',1)                          
            ->select('subscriber_review.*','rl.review_level','rl.designation','rl.position','rr.review_range')         
            ->first();
            
            if(!empty($subscriber_review_data)){
                $rep_area = RepresentationAreaList::where('rep_area_key',$subscriber_review_data->review_range)->where('is_deleted',0)->first();
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscriber Review Data','subscriber_review_data'=>$subscriber_review_data,'rep_area'=>$rep_area),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addReviewOfficial(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            $subscriber_data = CommonHelper::getSubscriberData($user->id);
            
            $subscriber_reviews = SubscriberReview::join('review_level as rl', 'rl.id', '=', 'subscriber_review.review_level_id')
            ->join('review_range as rr', 'rr.id', '=', 'subscriber_review.review_range_id')        
            ->where('subscriber_review.subscriber_id',$subscriber_data->id)
            ->where('subscriber_review.is_deleted',0)        
            ->where('rl.is_deleted',0)                
            ->where('subscriber_review.status',1)                        
            ->where('rl.status',1)                          
            ->select('subscriber_review.*','rl.review_level','rl.designation','rl.position','rr.review_range')        
            ->orderBy('rr.id')        
            ->get()->toArray();
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            
            $params = ['country_list'=>$country_list,'states_list'=>$states_list,'subscriber_reviews'=>$subscriber_reviews,'title'=>'Add Review Official'];
            
            return view('admin/subscriber/review_official_add',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function submitAddReviewOfficial(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            $user_data = [];
            $subscriber_data = CommonHelper::getSubscriberData($user->id);
            
            $validationRules = array('emailAddress'=>'required|email','subscriber_review_id'=>'required','reviewOfficialStatus'=>'required','officialName'=>'required','user_Name'=>'required');
            $attributes = array('emailAddress'=>'Email Address','subscriber_review_id'=>'Designation','reviewOfficialStatus'=>'Member Status','officialName'=>'Official Name','mobileNumber'=>'Mobile Number','DOB'=>'DOB','user_Name'=>'Username');
            
            $fields = ['country'=>'Country','state'=>'State','district'=>'District','LAC'=>'Legislative Assembly Constituency','PC'=>'Parliamentary Constituency',
            'MC1'=>'Municipal Corporation','MC2'=>'Municipality','CC'=>'City Council','block'=>'Block','ward'=>'Ward','subDistrict'=>'Sub District','village'=>'Village'];
            
            foreach($fields as $key=>$value){
                $attributes[$key.'_ro'] = $value;
            }
            
            if(!empty($data['emailAddress'])){
                $user_data = User::where('email',trim($data['emailAddress']))->where('is_deleted',0)->first();
                if(empty($user_data)){
                    $validationRules['officialName'] = $validationRules['user_Name'] = $validationRules['mobileNumber'] = $validationRules['gender'] = $validationRules['DOB'] = 'required';
                }
            }else{
                $validationRules['officialName'] = $validationRules['user_Name'] = $validationRules['mobileNumber'] = $validationRules['gender'] = $validationRules['DOB'] = 'required';
            }
            
            if(!empty($data['subscriber_review_id'])){
                $subscriber_review_data = SubscriberReview::join('review_level as rl', 'rl.id', '=', 'subscriber_review.review_level_id')
                ->join('review_range as rr', 'rr.id', '=', 'subscriber_review.review_range_id')        
                ->where('subscriber_review.subscriber_id',$subscriber_data->id)
                ->where('subscriber_review.id',$data['subscriber_review_id'])        
                ->where('subscriber_review.is_deleted',0)        
                ->where('rl.is_deleted',0)                
                ->where('rr.is_deleted',0)                      
                ->where('subscriber_review.status',1)                        
                ->where('rl.status',1)                          
                ->select('subscriber_review.*','rl.review_level','rl.designation','rl.position','rr.review_range')         
                ->first();
                
                if(empty($subscriber_review_data)){
                    return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Subscriber Review Data is empty', 'errors' => 'Subscriber Review Data is empty'));
                }

                $review_range = str_replace(' ','_',$subscriber_review_data->review_range);
                
                $rep_area = RepresentationAreaList::where('rep_area_key',$review_range)->where('is_deleted',0)->first();
                
                if(empty($rep_area)){
                    return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Representation Area Data is empty', 'errors' => 'Representation Area Data is empty'));
                }
                
                $req_fields = explode(',',$rep_area->rep_area_fields);
                        
                for($i=0;$i<count($req_fields);$i++){
                    $field = $req_fields[$i].'_ro';
                    $validationRules[$field] = 'required';
                }
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            if(empty($user_data)){
                $insertArray = array('name'=>trim($data['officialName']),'email'=>trim($data['emailAddress']),'mobile_no'=>trim($data['mobileNumber']),'gender'=>trim($data['gender']),
                'dob'=>trim($data['DOB']),'user_role'=>5,'password'=>Hash::make('12345678'),'user_name'=>trim($data['user_Name']),'official_name'=>trim($data['officialName']));
                
                $user_exists = User::where('email',trim($data['emailAddress']))->where('is_deleted',0)->first();
                if(!empty($user_exists)){
                    return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User already exists with Email Address', 'errors' => 'User already exists with Email Address'));
                }
                
                $user_exists = User::where('user_name',trim($data['user_Name']))->where('is_deleted',0)->first();
                if(!empty($user_exists)){
                    return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User already exists with Username', 'errors' => 'User already exists with Username'));
                }

                $user_data = User::create($insertArray);
            }
            
            $review_official_exists = ReviewOfficial::where('user_id',$user_data->id)->where('is_deleted',0)->first();
            if(!empty($review_official_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User already added as Review Official', 'errors' => 'User already added as Review Official'));
            }
            
            /*if($user_data->user_role != 3){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User Type is not General User', 'errors' => 'User Type is not General User'));
            }*/
            
            $insertArray = array('user_id'=>$user_data->id,'subscriber_review_id'=>trim($data['subscriber_review_id']),'subscriber_id'=>$subscriber_data->id,'status'=>$data['reviewOfficialStatus']);
            
            $fieldsArray = ['country_ro'=>'country_ro','state_ro'=>'state_ro','district_ro'=>'district_ro','lac_ro'=>'LAC_ro','pc_ro'=>'PC_ro','mc1_ro'=>'MC1_ro','mc2_ro'=>'MC2_ro',
            'cc_ro'=>'CC_ro','block_ro'=>'block_ro','ward_ro'=>'ward_ro','sub_district_ro'=>'subDistrict_ro','village_ro'=>'village_ro'];
            
            foreach($fieldsArray as $key=>$value){
                $insertArray[$key] = (isset($data[$value]) && !empty($data[$value]))?trim($data[$value]):null;
            }
            
            $reviewOfficial = ReviewOfficial::create($insertArray);
            
            $updateArray = ['reviewer_id'=>$reviewOfficial->id];
            User::where('id',$user_data->id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Review Official added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function editReviewOfficial(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            $subscriber_data = CommonHelper::getSubscriberData($user->id);
            $review_official_id = $id;
            
            $subscriber_reviews = SubscriberReview::join('review_level as rl', 'rl.id', '=', 'subscriber_review.review_level_id')
            ->join('review_range as rr', 'rr.id', '=', 'subscriber_review.review_range_id')        
            ->where('subscriber_review.subscriber_id',$subscriber_data->id)
            ->where('subscriber_review.is_deleted',0)        
            ->where('rl.is_deleted',0)                
            ->where('subscriber_review.status',1)                        
            ->where('rl.status',1)                          
            ->select('subscriber_review.*','rl.review_level','rl.designation','rl.position','rr.review_range')        
            ->orderBy('rr.id')        
            ->get()->toArray();
            
            $country_list = CountryList::where('is_deleted',0)->get()->toArray();
            $states_list = StateList::where('is_deleted',0)->get()->toArray();
            $ro_data = ReviewOfficial::where('id',$review_official_id)->where('is_deleted',0)->first();
            $user_data = User::where('id',$ro_data->user_id)->where('is_deleted',0)->first();
            
            $params = ['country_list'=>$country_list,'states_list'=>$states_list,'subscriber_reviews'=>$subscriber_reviews,'title'=>'Edit Review Official','ro_data'=>$ro_data,'user_data'=>$user_data];
            
            return view('admin/subscriber/review_official_edit',$params);
            
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage().', '.$e->getLine()));
        }
    }
    
    public function submitEditReviewOfficial(Request $request,$id){
        try{
            $data = $request->all();
            $user = Auth::user();
            $user_data = [];
            $subscriber_data = CommonHelper::getSubscriberData($user->id);
            
            $review_official_id = $id;
            $ro_data = ReviewOfficial::where('id',$review_official_id)->where('is_deleted',0)->first();
            
            $validationRules = array('emailAddress'=>'required|email','subscriber_review_id'=>'required','reviewOfficialStatus'=>'required','officialName'=>'required');
            $attributes = array('emailAddress'=>'Email Address','subscriber_review_id'=>'Designation','reviewOfficialStatus'=>'Member Status','userName'=>'Name','mobileNumber'=>'Mobile Number','DOB'=>'DOB','officialName'=>'Official Name');
            
            $fields = ['country'=>'Country','state'=>'State','district'=>'District','LAC'=>'Legislative Assembly Constituency','PC'=>'Parliamentary Constituency',
            'MC1'=>'Municipal Corporation','MC2'=>'Municipality','CC'=>'City Council','block'=>'Block','ward'=>'Ward','subDistrict'=>'Sub District','village'=>'Village'];
            
            foreach($fields as $key=>$value){
                $attributes[$key.'_ro'] = $value;
            }
            
            if(!empty($data['emailAddress'])){
                $user_data = User::where('email',trim($data['emailAddress']))->where('is_deleted',0)->first();
                if(empty($user_data)){
                    $validationRules['officialName'] = $validationRules['user_Name'] = $validationRules['mobileNumber'] = $validationRules['gender'] = $validationRules['DOB'] = 'required';
                }
            }else{
                $validationRules['officialName'] = $validationRules['user_Name'] = $validationRules['mobileNumber'] = $validationRules['gender'] = $validationRules['DOB'] = 'required';
            }
            
            if(!empty($data['subscriber_review_id'])){
                $subscriber_review_data = SubscriberReview::join('review_level as rl', 'rl.id', '=', 'subscriber_review.review_level_id')
                ->join('review_range as rr', 'rr.id', '=', 'subscriber_review.review_range_id')        
                ->where('subscriber_review.subscriber_id',$subscriber_data->id)
                ->where('subscriber_review.id',$data['subscriber_review_id'])        
                ->where('subscriber_review.is_deleted',0)        
                ->where('rl.is_deleted',0)                
                ->where('rr.is_deleted',0)                      
                ->where('subscriber_review.status',1)                        
                ->where('rl.status',1)                          
                ->select('subscriber_review.*','rl.review_level','rl.designation','rl.position','rr.review_range')         
                ->first();
                
                if(empty($subscriber_review_data)){
                    return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Subscriber Review Data is empty', 'errors' => 'Subscriber Review Data is empty'));
                }

                $review_range = str_replace(' ','_',$subscriber_review_data->review_range);
                
                $rep_area = RepresentationAreaList::where('rep_area_key',$review_range)->where('is_deleted',0)->first();
                
                if(empty($rep_area)){
                    return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Representation Area Data is empty', 'errors' => 'Representation Area Data is empty'));
                }
                
                $req_fields = explode(',',$rep_area->rep_area_fields);
                        
                for($i=0;$i<count($req_fields);$i++){
                    $field = $req_fields[$i].'_ro';
                    $validationRules[$field] = 'required';
                }
            }
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            if(empty($user_data)){
                $insertArray = array('name'=>trim($data['officialName']),'email'=>trim($data['emailAddress']),'mobile_no'=>trim($data['mobileNumber']),'gender'=>trim($data['gender']),
                'dob'=>trim($data['DOB']),'user_role'=>3,'password'=>Hash::make('12345678'),'official_name'=>trim($data['officialName']));
                
                $user_exists = User::where('email',trim($data['emailAddress']))->where('is_deleted',0)->first();
                if(!empty($user_exists)){
                    return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User already exists with Email Address', 'errors' => 'User already exists with Email Address'));
                }

                $user_data = User::create($insertArray);
            }
            
            $review_official_exists = ReviewOfficial::where('user_id',$user_data->id)->where('id','!=',$review_official_id)->where('is_deleted',0)->first();
            if(!empty($review_official_exists)){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User already added as Review Official', 'errors' => 'User already added as Review Official'));
            }
            
            if($user_data->user_role != 3){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'User Type is not General User', 'errors' => 'User Type is not General User'));
            }
            
            $updateArray = array('user_id'=>$user_data->id,'subscriber_review_id'=>trim($data['subscriber_review_id']),'status'=>$data['reviewOfficialStatus']);
            
            $fieldsArray = ['country_ro'=>'country_ro','state_ro'=>'state_ro','district_ro'=>'district_ro','lac_ro'=>'LAC_ro','pc_ro'=>'PC_ro','mc1_ro'=>'MC1_ro','mc2_ro'=>'MC2_ro',
            'cc_ro'=>'CC_ro','block_ro'=>'block_ro','ward_ro'=>'ward_ro','sub_district_ro'=>'subDistrict_ro','village_ro'=>'village_ro'];
            
            foreach($fieldsArray as $key=>$value){
                $updateArray[$key] = (isset($data[$value]) && !empty($data[$value]))?trim($data[$value]):null;
            }
            
            ReviewOfficial::where('id',$review_official_id)->update($updateArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Review Official updated successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function listReviewOfficial(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            $subscriber_data = CommonHelper::getSubscriberData($user->id);
            
            $ro_list = ReviewOfficial::join('subscriber_review as sr', 'sr.id', '=', 'review_official.subscriber_review_id')
            ->join('review_level as rl', 'rl.id', '=', 'sr.review_level_id')           
            ->join('users as u1', 'u1.id', '=', 'review_official.user_id')        
            ->join('users as u2', 'u2.id', '=', 'review_official.subscriber_id')        
            ->where('review_official.subscriber_id',$subscriber_data->id)                
            ->where('review_official.is_deleted',0)        
            ->where('sr.is_deleted',0)                
            ->where('u1.is_deleted',0)                
            ->where('u2.is_deleted',0);
            
            if(isset($data['ro_name']) && !empty($data['ro_name'])){
                $ro_list = $ro_list->where('u1.name','LIKE','%'.trim($data['ro_name']).'%');
            }
            
            $ro_list = $ro_list->select('review_official.*','u1.name as ro_name','u2.name as subscriber_name','rl.designation')        
            ->orderBy('review_official.id','ASC')
            ->paginate(50);
            
            return view('admin/subscriber/review_official_list',array('title'=>'Review Official List','ro_list'=>$ro_list));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function updateReviewOfficial(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $ids = explode(',',trim($data['ids']));
            
            if(empty($data['ids'])){
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Please select Review Official', 'errors' => 'Please select Review Official'));
            }
            
            if($data['action'] == 'delete'){
                ReviewOfficial::wherein('id',$ids)->update(['is_deleted'=>1]);
            }
            
            if($data['action'] == 'disable'){
                ReviewOfficial::wherein('id',$ids)->update(['status'=>0]);
            }
            
            if($data['action'] == 'enable'){
                ReviewOfficial::wherein('id',$ids)->update(['status'=>1]);
            }
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Review Official updated successfully'),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function getSubscribersList(Request $request){ 
        try{
            $data = $request->all();
            $user = Auth::user();
            $subscribers_list_person = $subscribers_list_org = [];
            
            $headers = CommonHelper::getAPIHeaders();
            $query_string = 'user_id='.$user->id.'&'.$_SERVER['QUERY_STRING'];
            $url = url('/api/subscribers/list?'.$query_string);
            
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);//exit;
            $response = json_decode($response,true);
            $subscribers_list = isset($response['subscribers_list'])?$response['subscribers_list']:[];
            
            for($i=0;$i<count($subscribers_list);$i++){
                if($subscribers_list[$i]['group_sub_type'] == 'person'){
                    $subscribers_list_person[] = $subscribers_list[$i];
                }else{
                    $subscribers_list_org[] = $subscribers_list[$i];
                }
            }
            
            return view('front/subscriber/subscriber_list',array('title'=>'Search Group','subscribers_person'=>$subscribers_list_person,'subscribers_org'=>$subscribers_list_org));
            
        }catch (\Exception $e){
            \DB::rollBack();
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }  
    }
    
    function addSubscriberFollower(Request $request){
        try{ 
            $data = $request->all();
            $user = Auth::user();
            $validationRules = array('subscriber_id'=>'required');
            $attributes = array('subscriber_id'=>'Subscriber');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Missing Required Fields', 'errors' => $validator->errors()),200);
            }	
            
            $post_data = $data; 
            $post_data['user_id'] = $user->id;
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/subscriber/follow');
            $response = CommonHelper::processCURLRequest($url,json_encode($post_data),'','',$headers);
            $response = json_decode($response,true);
            
            return $response;
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    function deleteSubscriberFollower(Request $request){
        try{ 
            $data = $request->all();
            $user = Auth::user();
            $validationRules = array('subscriber_id'=>'required');
            $attributes = array('subscriber_id'=>'Subscriber');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Missing Required Fields', 'errors' => $validator->errors()),200);
            }	
            
            $post_data = $data; 
            $post_data['user_id'] = $user->id;
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/subscriber/unfollow');
            $response = CommonHelper::processCURLRequest($url,json_encode($post_data),'','',$headers);
            $response = json_decode($response,true);
            
            return $response;
            
        }catch (\Exception $e){
            CommonHelper::saveException($e,'STORE',__FUNCTION__,__FILE__);
            return response(array('httpStatus'=>200,"dateTime"=>time(),'status' => 'fail','error_message'=>$e->getMessage(),'message'=>'Error in Processing Request'),200);
        }    
    }
    
    public function viewSubscriberProfile(Request $request,$subscriber_id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/subscriber/data/'.$subscriber_id);
            
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);
            $response = json_decode($response,true);
            $subscriber_data = isset($response['subscriber_data'])?$response['subscriber_data']:[];
            
            $params = ['title'=>'View Subscriber Profile','subscriber_data'=>$subscriber_data];
            
            return view('front/subscriber/subscriber_profile_view',$params);
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage().', '.$e->getLine()));
        }
    }
    
    public function listSubscriberFollowers(Request $request,$subscriber_id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $query_string = 'user_id='.$user->id.'&'.$_SERVER['QUERY_STRING'];
            $url = url('/api/subscriber/followers/'.$subscriber_id.'?'.$query_string);
            
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);
            $response = json_decode($response,true);
            $subscriber_followers = isset($response['subscriber_followers'])?$response['subscriber_followers']:[];
            
            $params = ['title'=>'View Subscriber Followers','subscriber_followers'=>$subscriber_followers];
            
            return view('front/subscriber/subscriber_followers_list',$params);
            
        }catch (\Exception $e){
            return view('front/page_error',array('message' =>$e->getMessage().', '.$e->getLine()));
        }
    }
}
