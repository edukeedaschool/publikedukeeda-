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
            'subscriberStatus'=>'required','password'=>'required|min:6|max:100');
            
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
                    $validationRules['subscriberGender'] = 'required';$validationRules['subscriberDOB'] = 'required';$validationRules['politicalParty'] = 'required';
                    $validationRules['politicalPartyOfficialPosition'] = 'required';$validationRules['repAreaOfficialPartyPosition'] = 'required';
                    $validationRules['electedOfficialPositionName'] = 'required';$validationRules['repAreaElectedOfficialPosition'] = 'required';
                    
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
            
            $image_name = CommonHelper::uploadImage($request,$request->file('subscriberImage'),'images/user_images');
            
            $insertArray = array('name'=>trim($data['subscriberName']),'email'=>trim($data['emailAddress']),'mobile_no'=>trim($data['mobileNumber']),'address_line1'=>trim($data['addressLine1']),
            'postal_code'=>trim($data['postalCode']),'user_role'=>2,'country'=>trim($data['country']),'state'=>trim($data['state']),'district'=>trim($data['district']),
            'sub_district'=>trim($data['subDistrict']),'village'=>trim($data['village']),'sub_district'=>trim($data['subDistrict']),'image'=>$image_name,'password'=>Hash::make($data['password']));
                
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
            'cc_eo'=>'CC_eo','block_eo'=>'block_eo','ward_eo'=>'ward_eo','sub_district_eo'=>'subDistrict_eo','village_eo'=>'village_eo','status'=>'subscriberStatus'];
            
            foreach($fieldsArray as $key=>$value){
                $insertArray[$key] = (isset($data[$value]) && !empty($data[$value]))?trim($data[$value]):null;
            }
            
            $insertArray['user_id'] = $user_data->id;
            
            $subscriber = SubscriberList::create($insertArray);
          
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Subscriber added successfully'),200);
            
        }catch (\Exception $e){
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    function getLocList(){
        $loc_list = [];
        $loc_list['country'] = 'country'; $loc_list['state'] = 'country,state'; $loc_list['district'] = 'country,state,district';  $loc_list['sub_district'] = 'country,state,district,subDistrict';
        $loc_list['legislative_assembly_constituency'] = 'country,state,district,LAC';$loc_list['parliamentary_constituency'] = 'country,state,district,PC';
        $loc_list['municipal_corporation'] = 'country,state,MC1';$loc_list['municipality'] = 'country,state,district,MC2';$loc_list['city_council'] = 'country,state,district,CC';
        $loc_list['block'] = 'country,state,district,block';$loc_list['ward'] = 'country,state,district,CC,ward';$loc_list['village'] = 'country,state,district,subDistrict,village';

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
            
            if($rep_area_pp == 'sub_district'){
                $rep_area_pp_district_def_val = $subscriber_data->sub_district_pp;
            }elseif($rep_area_pp == 'municipal_corporation'){
                $rep_area_pp_district_def_val = $subscriber_data->mc1_pp;
            }elseif($rep_area_pp == 'village'){
                $rep_area_pp_district_def_val = $subscriber_data->village_pp;
            }elseif($rep_area_pp == 'municipality'){
                $rep_area_pp_district_def_val = $subscriber_data->mc2_pp;
            }elseif($rep_area_pp == 'city_council'){
                $rep_area_pp_district_def_val = $subscriber_data->cc_pp;
            }elseif($rep_area_pp == 'ward'){
                $rep_area_pp_district_def_val = $subscriber_data->ward_pp;
            }elseif($rep_area_pp == 'block'){
                $rep_area_pp_district_def_val = $subscriber_data->block_pp;
            }elseif($rep_area_pp == 'legislative_assembly_constituency'){
                $rep_area_pp_district_def_val = $subscriber_data->lac_pp;
            }elseif($rep_area_pp == 'parliamentary_constituency'){
                $rep_area_pp_district_def_val = $subscriber_data->pc_pp;
            }
            
            
            $rep_area_eo = $subscriber_data->rep_area_elec_off_pos;
            
            /*if($rep_area_eo == 'district'){
                $rep_area_eo_state_def_val = $subscriber_data->district_eo;
            }
            if($rep_area_eo == 'municipal_corporation'){
                $rep_area_eo_state_def_val = $subscriber_data->mc1_eo;
            }*/
            
            if($rep_area_eo == 'sub_district'){
                $rep_area_eo_district_def_val = $subscriber_data->sub_district_eo;
            }elseif($rep_area_eo == 'municipal_corporation'){
                $rep_area_eo_district_def_val = $subscriber_data->mc1_eo;
            }elseif($rep_area_eo == 'village'){
                $rep_area_eo_district_def_val = $subscriber_data->village_eo;
            }elseif($rep_area_eo == 'municipality'){
                $rep_area_eo_district_def_val = $subscriber_data->mc2_eo;
            }elseif($rep_area_eo == 'city_council'){
                $rep_area_eo_district_def_val = $subscriber_data->cc_eo;
            }elseif($rep_area_eo == 'ward'){
                $rep_area_eo_district_def_val = $subscriber_data->ward_eo;
            }elseif($rep_area_eo == 'block'){
                $rep_area_eo_district_def_val = $subscriber_data->block_eo;
            }elseif($rep_area_eo == 'legislative_assembly_constituency'){
                $rep_area_eo_district_def_val = $subscriber_data->lac_eo;
            }elseif($rep_area_eo == 'parliamentary_constituency'){
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
                    $validationRules['subscriberGender'] = 'required';$validationRules['subscriberDOB'] = 'required';$validationRules['politicalParty'] = 'required';
                    $validationRules['politicalPartyOfficialPosition'] = 'required';$validationRules['repAreaOfficialPartyPosition'] = 'required';
                    $validationRules['electedOfficialPositionName'] = 'required';$validationRules['repAreaElectedOfficialPosition'] = 'required';
                    
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
            'cc_eo'=>'CC_eo','block_eo'=>'block_eo','ward_eo'=>'ward_eo','sub_district_eo'=>'subDistrict_eo','village_eo'=>'village_eo','status'=>'subscriberStatus'];
            
            foreach($fieldsArray as $key=>$value){
                $updateArray[$key] = (isset($data[$value]) && !empty($data[$value]))?trim($data[$value]):null;
            }
            
            $subscriber = SubscriberList::where('id',$subscriber_id)->update($updateArray);
            
            $updateArray = array('name'=>trim($data['subscriberName']),'mobile_no'=>trim($data['mobileNumber']),'address_line1'=>trim($data['addressLine1']),
            'postal_code'=>trim($data['postalCode']),'country'=>trim($data['country']),'state'=>trim($data['state']),'district'=>trim($data['district']),
            'sub_district'=>trim($data['subDistrict']),'village'=>trim($data['village']),'sub_district'=>trim($data['subDistrict']));
            
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
            $review_levels = $subscriber_reviews = $subscriber_reviews_list = [];
            
            $subscriber_reviews = SubscriberReview::join('review_level as rl', 'rl.id', '=', 'subscriber_review.review_level_id')
            ->join('review_range as rr', 'rr.id', '=', 'subscriber_review.review_range_id')        
            ->where('subscriber_review.subscriber_id',$user->id)
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
            $subscriber_id = $user->id;
            
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
            
            $subscriber_reviews = SubscriberReview::join('review_level as rl', 'rl.id', '=', 'subscriber_review.review_level_id')
            ->join('review_range as rr', 'rr.id', '=', 'subscriber_review.review_range_id')        
            ->where('subscriber_review.subscriber_id',$user->id)
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
    
}
