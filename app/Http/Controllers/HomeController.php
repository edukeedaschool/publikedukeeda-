<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Helpers\CommonHelper;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        try{
            $data = $request->all();
            $user = Auth::user();
            $qs_str = '';
            $submissions_all = $submissions_pending = $submissions_closed = $submissions_my = [];
            
            /*if(! ($user->status == 1 && $user->is_deleted == 0)){
                
                Session::flush();
        
                Auth::logout();
        
                return redirect('/login');
            }*/
            
            if(! ((isset($user->id) && !empty($user->id)))){
                return redirect('user/login');
            }
            
            $headers = CommonHelper::getAPIHeaders();
            
            if($user->user_role == 2){
                $subscriber_id = !empty($user->subscriber_id)?$user->subscriber_id:0;
                
                $qs_str = 'subscriber_id='.$subscriber_id;
                $url = url('/api/submissions/list?'.$qs_str);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);
                $response = json_decode($response,true);
                $submissions_all = isset($response['submissions'])?$response['submissions']:[];
                
                $qs_str = 'subscriber_id='.$subscriber_id.'&sub_type=pending';
                $url = url('/api/submissions/list?'.$qs_str);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);
                $response = json_decode($response,true);
                $submissions_pending = isset($response['submissions'])?$response['submissions']:[];
                
                $qs_str = 'subscriber_id='.$subscriber_id.'&sub_type=closed';
                $url = url('/api/submissions/list?'.$qs_str);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);
                $response = json_decode($response,true);
                $submissions_closed = isset($response['submissions'])?$response['submissions']:[];
                
            }elseif($user->user_role == 3){
                
                $qs_str = 'user_id='.$user->id;
                $url = url('/api/submissions/list?'.$qs_str);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);
                $response = json_decode($response,true);
                $submissions_all = isset($response['submissions'])?$response['submissions']:[];
                
                $qs_str = 'user_id='.$user->id.'&sub_type=my';
                $url = url('/api/submissions/list?'.$qs_str);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);
                $response = json_decode($response,true);
                $submissions_my = isset($response['submissions'])?$response['submissions']:[];
            
            }elseif($user->user_role == 5){
                $reviewer_id = !empty($user->reviewer_id)?$user->reviewer_id:0;
                
                $qs_str = 'reviewer_id='.$reviewer_id;
                $url = url('/api/submissions/list?'.$qs_str);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);
                $response = json_decode($response,true);
                $submissions_all = isset($response['submissions'])?$response['submissions']:[];
            }else{
            
                $headers = CommonHelper::getAPIHeaders();
                $url = url('/api/submissions/list?'.$qs_str);
                $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);
                $response = json_decode($response,true);

                $submissions_all = isset($response['submissions'])?$response['submissions']:[];
            }
            
            return view('home',array('user'=>$user,'title'=>'Home Page','submissions_all'=>$submissions_all,'submissions_my'=>$submissions_my,
            'submissions_pending'=>$submissions_pending,'submissions_closed'=>$submissions_closed));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function accessDenied(Request $request){
        return view('access_denied',array('title'=>'Access Denied'));
    }
}
