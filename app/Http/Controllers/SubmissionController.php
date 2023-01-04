<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Helpers\CommonHelper;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubmissionController extends Controller
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

    
    public function addSubmissionSubscriber(Request $request){
        try{
            $data = $request->all();
            $states_list = [];
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/sub-group/list');
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);
            $response = json_decode($response,true);
            $sub_group_list = $response['sub_group_list'];//print_r($sub_group_list);
            
            
            return view('front/submission/submission_subscriber_add',array('user'=>$user,'title'=>'Add Submission','sub_group_list'=>$sub_group_list));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function getSubmissionSubscribersList(Request $request,$sub_group_id,$user_id){ 
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/submission-subscribers/list/'.$sub_group_id.'/'.$user_id);
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);exit;
            $response = json_decode($response,true);
            $subscriber_list = isset($response['subscriber_list'])?$response['subscriber_list']:[];
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Subscribers List','subscriber_list'=>$subscriber_list),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
    
    public function addSubmissionType(Request $request,$subscriber_id){
        try{
            $data = $request->all();
            $states_list = [];
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/submission-subscriber/data/'.$subscriber_id);
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);
            $response = json_decode($response,true);
            
            $subscriber_data = $response['subscriber_data'];
            //$submission_purpose_list = $response['submission_purpose_list'];
            $submission_type_list = $response['submission_type_list'];
            
            return view('front/submission/submission_type_add',array('user'=>$user,'title'=>'Add Submission','subscriber_data'=>$subscriber_data,'submission_type_list'=>$submission_type_list));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function addSubmissionDetail(Request $request,$subscriber_id,$submission_type_id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/submission-subscriber/data/'.$subscriber_id);
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);
            $response = json_decode($response,true);
            
            $subscriber_data = $response['subscriber_data'];
            $submission_purpose_list = $response['submission_purpose_list'];
           
            return view('front/submission/submission_detail_add',array('user'=>$user,'title'=>'Add Submission','subscriber_data'=>$subscriber_data,'submission_purpose_list'=>$submission_purpose_list,'submission_type_id'=>$submission_type_id));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function saveSubmissionDetail(Request $request){
        try{
            $data = $request->all();
            $access_token = '';
            $user = Auth::user();
            
            $validationRules = array('purpose'=>'required','nature'=>'required','subject'=>'required','summary'=>'required','subscriber_id'=>'required',
            'submission_type_id'=>'required','detail_file'=>'required|mimes:jpeg,png,jpg,gif,pdf|max:3072');
            $attributes = array('subscriber_id'=>'Subscriber','submission_type_id'=>'Submission Type');
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $headers = CommonHelper::getAPIHeaders();
            for($i=0;$i<count($headers);$i++){
                if(strpos($headers[$i],'Access-') !== false){
                    $access_token = $headers[$i];
                }
            }
            
            $file = $request->file('detail_file');
            $filePath=  ($file->getRealPath());
            $fileMime = $file->getMimeType(); 
            $fileName = $file->getClientOriginalName(); 
            $cfile = new \CURLFile( $filePath,$fileMime,$fileName);

            $postFileDataArray = $data;
            $postFileDataArray['detail_file'] = $cfile;
            $postFileDataArray['user_id'] = $user->id;

            $method = 'POST'; 
            $url    = url('/api/submission/detail/save');
            
            $curl = curl_init();	
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_URL, $url);
            //curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array($access_token,'Content-Type: multipart/form-data','Accept: application/json'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$postFileDataArray);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            $response = curl_exec($curl);
            curl_close($curl);
           
            return $response;
         
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }
    }
    
    public function addSubmissionConfirm(Request $request,$submission_id){
        try{
            $data = $request->all();
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/submission/data/'.$submission_id);
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);
            $response = json_decode($response,true);
            
            $submission_data = $response['submission_data'];
            
            return view('front/submission/submission_confirm_add',array('user'=>$user,'title'=>'Confirm Submission','submission_data'=>$submission_data));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function saveSubmissionConfirm(Request $request){
        try{
            $data = $request->all();
            $access_token = '';
            $user = Auth::user();
            
            $validationRules = array('submission_id'=>'required');
            $attributes = [];
            
            $validator = Validator::make($data,$validationRules,array(),$attributes);
            if ($validator->fails()){ 
                return response(array('httpStatus'=>200, "dateTime"=>time(), 'status'=>'fail', 'message'=>'Validation error', 'errors' => $validator->errors()));
            }	
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/submission/confirm/save');
            $post_data = ['submission_id'=>trim($data['submission_id'])];
            $response = CommonHelper::processCURLRequest($url,json_encode($post_data),'','',$headers);//print_r($response);exit;
            $response = json_decode($response,true);
            
            return $response;
         
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }
    }

}
