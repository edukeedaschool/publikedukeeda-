<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
            $states_list = [];
            $user = Auth::user();
            
            /*if(! ($user->status == 1 && $user->is_deleted == 0)){
                
                Session::flush();
        
                Auth::logout();
        
                return redirect('/login');
            }*/
            
            return view('home',array('user'=>$user,'title'=>'Home Page'));
         
        }catch (\Exception $e){
            return view('admin/page_error',array('message' =>$e->getMessage()));
        }
    }
    
    public function accessDenied(Request $request){
        return view('access_denied',array('title'=>'Access Denied'));
    }
}
