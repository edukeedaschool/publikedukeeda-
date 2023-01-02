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

    
    public function addSubmission(Request $request){
        try{
            $data = $request->all();
            $states_list = [];
            $user = Auth::user();
            
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/sub-group/list');
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);
            $response = json_decode($response,true);
            $sub_group_list = $response['sub_group_list'];//print_r($sub_group_list);
            
            /*$user_id = 1;$sub_group_id = 1;
            $headers = CommonHelper::getAPIHeaders();
            $url = url('/api/submission-subscribers/list/'.$sub_group_id.'/'.$user_id);
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);print_r($response);
            $response = json_decode($response,true);
            $subscriber_list = $response['subscriber_list'];*/
            
            return view('front/submission/submission_add',array('user'=>$user,'title'=>'Add Submission','sub_group_list'=>$sub_group_list));
         
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
            $response = CommonHelper::processCURLRequest($url,'','','',$headers);
            $response = json_decode($response,true);
            $subscriber_list = $response['subscriber_list'];
            
            return response(array('httpStatus'=>200, 'dateTime'=>time(), 'status'=>'success','message' => 'Submission Subscribers List','subscriber_list'=>$subscriber_list),200);
            
        }catch (\Exception $e){
            \DB::rollBack();
            return response(array("httpStatus"=>500,"dateTime"=>time(),'status' => 'fail','message' =>$e->getMessage()),500);
        }  
    }
}
