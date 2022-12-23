<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use App\Models\User_links;
use App\Models\App_logs;
use App\Models\RepresentationAreaList;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Carbon\Carbon;
use PDF;
        
class CommonHelper
{
    public static function getCurrency($params = array())
    {
        return 'INR';
    }
    
    public static function getDBArray($array,$key,$value){
        $dbArray = array();
        
        if(isset($array[0]) && !is_array($array[0])){
            $str = json_encode($array);
            $array = json_decode($str,true);
        }
        
        for($i=0;$i<count($array);$i++){
            if($array[$i][$key] == $value){
                $dbArray[] = $array[$i];
            }
        }
        
        return $dbArray;
    }
    
    public static function getArrayRecord($array,$key,$value){
        $dbArray = array();
        if(isset($array[0]) && !is_array($array[0])){
            $str = json_encode($array);
            $array = json_decode($str,true);
        }
        
        for($i=0;$i<count($array);$i++){
            if(strtolower($array[$i][$key]) == strtolower($value)){
                $dbArray = $array[$i];
                break;
            }
        }
        
        return $dbArray;
    }
    
    public static function DBArrayExists($array,$key,$value){
        $value_exists = false;
        
        if(isset($array[0]) && !is_array($array[0])){
            $str = json_encode($array);
            $array = json_decode($str,true);
        }
        
        for($i=0;$i<count($array);$i++){
            if($array[$i][$key] == $value){
                $value_exists = true;
                break;
            }
        }
        
        return $value_exists;
    }
    
    public static function searchArrayByKeys($source_array,$search_array){
        if(isset($source_array[0]) && !is_array($source_array[0])){
            $str = json_encode($source_array);
            $source_array = json_decode($str,true);
        }
        
        $val_exists = $index = false;
        for($i=0;$i<count($source_array);$i++){
            foreach($search_array as $key=>$value){
                if(strtolower($source_array[$i][$key]) == strtolower($value)){
                    $val_exists = true;
                }else{
                    $val_exists = false;
                    break;
                }
            }
            
            if($val_exists == true){
                $index = $i;
                break;
            }
        }
        
        return $index;
    }
    
    public static function getSortOrder($sort_by,$default_sort_column = false,$default_sort_order = 'ASC'){
        if($default_sort_column){            
            if(!isset($_GET['sort_by'])){
                if(strtolower($default_sort_order) == 'asc') $sort_order =  'DESC';else $sort_order =  'ASC';
            }else{
                $sort_order = 'ASC';
            }
        }else{
            $sort_order = 'ASC';
        } 
        
        if(isset($_GET['sort_by']) && strtolower($_GET['sort_by']) == strtolower($sort_by) && isset($_GET['sort_order'])){
           $sort_order = (strtolower($_GET['sort_order']) == 'asc')?'DESC':'ASC';
        }
        
        return $sort_order;
    }
    
    public static function getSortIcon($sort_by){
        $icon = '';
        
        if(isset($_GET['sort_by']) && strtolower($_GET['sort_by']) == strtolower($sort_by) && isset($_GET['sort_order'])){
            $icon = (strtolower($_GET['sort_order']) == 'asc')?'<i class="fas fa-angle-up"></i>':'<i class="fas fa-angle-down"></i>';
        }
        
        return $icon;
    }
    
    public static function getDefaultSortIcon($default_sort_order = 'ASC'){
        if(strtolower($default_sort_order) == 'asc')
            return '<i class="fas fa-angle-up"></i>';
        else
            return '<i class="fas fa-angle-down"></i>';
    }
    
    public static function getSortingOrder(){
        return (!empty(request('sort_order')) && in_array(strtolower(request('sort_order')),array('asc','desc')))?strtoupper(request('sort_order')):'ASC';
    }
    
    public static function getSortLink($name,$sort_by,$url,$default_sort_column = false,$default_sort_order = 'ASC'){
        $link = '<a href="'.url($url).'?sort_by='.$sort_by.'&sort_order='.CommonHelper::getSortOrder($sort_by,$default_sort_column,$default_sort_order).'">'.$name.' ';
        if($default_sort_column)
            if(empty(request('sort_by'))) $link.=CommonHelper::getDefaultSortIcon($default_sort_order);else $link.=CommonHelper::getSortIcon($sort_by);
        else
            $link.=CommonHelper::getSortIcon($sort_by);
        return $link.'</a>';
    }
    
    
    
    public static function createLog($title,$key,$type,$params=array()){
        $user = \Auth::user();
        $insertArray = array('log_title'=>$title,'log_key'=>$key,'log_type'=>$type);
        if(isset($user->id) && !empty($user->id)){
            $insertArray['user_id'] = $user->id;
            $insertArray['role_id'] = $user->user_type;
        }
        App_logs::create($insertArray);
    }
    
    public static function createResizedImage($imagePath,$newPath,$newWidth,$newHeight =0,$outExt = 'DEFAULT')
    {
        if (!$newPath or !file_exists ($imagePath)) {
            return null;
        }

        $types = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_BMP, IMAGETYPE_WEBP];
        //$type = exif_imagetype ($imagePath);
        $typeData = getimagesize($imagePath);
        $type = $typeData[2];

        if (!in_array ($type, $types)) {
            return null;
        }

        list ($width, $height) = getimagesize ($imagePath);

        $outBool = in_array ($outExt, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg ($imagePath);
                if (!$outBool) $outExt = 'jpg';
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng ($imagePath);
                if (!$outBool) $outExt = 'png';
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif ($imagePath);
                if (!$outBool) $outExt = 'gif';
                break;
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp ($imagePath);
                if (!$outBool) $outExt = 'bmp';
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp ($imagePath);
                if (!$outBool) $outExt = 'webp';
        }

        $newImage = imagecreatetruecolor ($newWidth, $newHeight);

        //TRANSPARENT BACKGROUND
        $color = imagecolorallocatealpha ($newImage, 0, 0, 0, 127); //fill transparent back
        imagefill ($newImage, 0, 0, $color);
        imagesavealpha ($newImage, true);

        //ROUTINE
        imagecopyresampled ($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        switch (true) {
            case in_array ($outExt, ['jpg', 'jpeg']): $success = imagejpeg ($newImage, $newPath);
                break;
            case $outExt === 'png': $success = imagepng ($newImage, $newPath);
                break;
            case $outExt === 'gif': $success = imagegif ($newImage, $newPath);
                break;
            case  $outExt === 'bmp': $success = imagebmp ($newImage, $newPath);
                break;
            case  $outExt === 'webp': $success = imagewebp ($newImage, $newPath);
        }

        if (!$success) {
            return null;
        }

        return $newPath;
    }
    
    public static function createBreadCrumb($array){
        $str = '<ol class="breadcrumb">';        
        for($i=0;$i<count($array);$i++){
            if(isset($array[$i]['link'])){
                $str.='<li class="breadcrumb-item"><a href="'.url($array[$i]['link']).'">'.$array[$i]['name'].'  </a></li>';
            }else{
                $str.='<li  class="breadcrumb-item active" aria-current="page">'.$array[$i]['name'].' </li>';
            }
        }
        
        $str.='</ol>';
        return $str;
    }
    
    public static function headerMessages(){
        $str = '<div style="clear:both;height:15px;"></div>';
    
        if (session('error_message')){
            $str.= '<br/>
            <div class="alert alert-danger">
                '.session('error_message').'
            </div>';
        }

        if(session('success_message')){
            $str.='<br/>
            <div class="alert alert-success">
                '.session('success_message').'
            </div>';
        }

       return $str;
    }
    
    public static function pageSubHeader($breadCrumbArray,$pageHeading){
        $str = '<nav class="page_bbreadcrumb" aria-label="breadcrumb">'.CommonHelper::createBreadCrumb($breadCrumbArray).'</nav>';
        $str.='<h2 class="page_heading page-heading">'.$pageHeading.' </h2>';
        $str.=CommonHelper::headerMessages();
        return $str;
    }
    
    public static function displayPageErrorMsg($error_message){
        $str = '';
        if(isset($error_message) && !empty($error_message)){
            $str.='<div class="alert alert-danger">'.$error_message.'</div>';
        }
        return $str;
    }
    
    
    
    public static function saveException($e,$type,$function_name,$file_name){
        CommonHelper::createLog('Exception: '.$e->getMessage().'. Method: '.$function_name. '. File: '.basename($file_name).'. Line No: '.$e->getLine(),'EXCEPTION',$type);
    }
    
    public static function uploadImage($request,$file_name,$dest_folder,$create_thumb=true,$thumb_folder='thumbs'){
        
        CommonHelper::createDirectory($dest_folder);
        if($create_thumb){
            CommonHelper::createDirectory($dest_folder.'/'.$thumb_folder);
        }
        
        $image = (is_object($file_name))?$file_name:$request->file($file_name);
        $image_name_text = substr($image->getClientOriginalName(),0,strpos($image->getClientOriginalName(),'.'));
        $image_name_text = substr(str_replace(' ','_',strtolower($image_name_text)),0,150);
        $image_ext = $image->getClientOriginalExtension();
        
        for($i=0;$i<1000;$i++){
            $image_name = ($i == 0)?$image_name_text.'.'.$image_ext:$image_name_text.'_'.$i.'.'.$image_ext;
            if(!file_exists(public_path($dest_folder.'/'.$image_name))){
                break;
            }
        }
        
        if(!isset($image_name)){
            $image_name = $image_name_text.'_'.rand(1000,100000).'.'.$image_ext;
        }
        
        $image->move(public_path($dest_folder), $image_name);

        if($create_thumb){
            $src = public_path($dest_folder).'/'.$image_name;
            $dest = public_path($dest_folder).'/'.$thumb_folder.'/'.$image_name;
            CommonHelper::createResizedImage($src,$dest,350,450);
        }
        
        return $image_name;
    }
    
    public static function createDirectory($path){
        if(!file_exists(public_path($path))){
            mkdir(public_path($path));
            chmod(public_path($path), 0777);
        }
    }
    
    
    public static function dateDiff($date1,$date2){
        $dt1 = new DateTime($date1);
        $dt2 = new DateTime($date2);

        return $diff = $dt2->diff($dt1)->format("%a");
    }
    
    
    public static function currencyFormat($num){
        $explrestunits = "" ;
        $num = preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $des = "00";
        if(count($words)<=2){
            $num=$words[0];
            if(count($words)>=2){$des=$words[1];}
            if(strlen($des)<2){$des="$des";}else{$des=substr($des,0,2);}
        }
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){
                // creates each of the 2's group and adds a comma to the end
                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return "$thecash.$des"; // writes the final format where $currency is the currency symbol.
    }
    
    public static function getQueryString(){
        return $_SERVER['QUERY_STRING'];
    }
    
    public static function formatDate($date){
        //convert dd/mm/yyyy  to yyyy/mm/dd
        if(!empty($date)){
            $dob_arr = explode('/',str_replace('-','/',trim($date)));
            $dob = (is_array($dob_arr) && count($dob_arr) == 3)?$dob_arr[2].'/'.$dob_arr[1].'/'.$dob_arr[0]:null;
        }else{
            $dob = null;
        }
        
        return $dob;
    }
    
    public static function convertUserDateToDBDate($date){
        $date = str_replace('-','/',trim($date));
        $date_arr = explode('/',$date);
        $date = $date_arr[2].'/'.$date_arr[1].'/'.$date_arr[0];
        
        return $date;
    }
    
    // Date format  yyyy/mm/dd
    public static function isValidDate($date){
        if(empty($date)){
            return false;
        }
        
        $date = str_replace('-','/',trim($date));
        $date_arr = explode('/',$date);
        
        if(is_array($date_arr) && count($date_arr) == 3){
            return checkdate($date_arr[1], $date_arr[2], $date_arr[0]);
        }else{
            return false;
        }
    }
    
    public static function displayDownloadDialogButton($title = 'Report'){
        return '<a href="javascript:;" onclick="downloadReportData();" class="btn btn-dialog" title="Download '.$title.' CSV File"><i title="Download '.$title.' CSV File" class="fa fa-download fas-icon" ></i> </a>';
    }
    
    public static function getDownloadPagingData($rec_count){
        $rec_count_arr = explode('_',$rec_count);
        $start = $rec_count_arr[0];
        $start = $start-1;
        $end = $rec_count_arr[1];
        $limit = $end-$start;
        
        return ['start'=>$start,'limit'=>$limit];
    }
    
    public static function filterCsvData($str){
        return str_replace(['"',"'"],['',''],trim($str));
    }
    
    public static function filterCsvInteger($value){
        return $value."\t";
    }
    
    public static function processCURLRequest($url,$post_data='',$username='',$password='',$headers = array(),$return_output = false,$delete = false,$put = false){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(!empty($post_data)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
        }
        if(!empty($username) && !empty($password)){
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        }

        if($delete){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        if($put){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        }

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10); 
        curl_setopt($ch, CURLOPT_TIMEOUT ,20); 

        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $server_output = curl_exec ($ch);

        if(!$return_output){
            curl_close ($ch);
            return $server_output;
        }else{
            $info = curl_getinfo($ch);
            $info['curl_error'] = curl_error($ch);
            curl_close ($ch);
            return array('output'=>$server_output,'curl_info'=>$info);
        }
    }
    
    public static function validateMobileNumber($mobile) {
        if(!empty($mobile)) {
            $isMobileNmberValid = TRUE;
            $mobileDigitsLength = strlen($mobile);
            if ($mobileDigitsLength < 10 || $mobileDigitsLength > 15) {
                $isMobileNmberValid = FALSE;
            } else {
                if (!preg_match("/^[+]?[1-9][0-9]{9,14}$/", $mobile)) {
                $isMobileNmberValid = FALSE;
                }
            }
          return $isMobileNmberValid;
        }else{
          return false;
        }
    }
    
    public static function getUserLinkedIDs($user){
        // Fetch links of current logged in user. Account manager -> Leads.  Lead -> Agents
        $user_links = User_links::where('user_id',$user->id)->where('is_deleted',0)->select('user_id_linked')->get()->toArray();
        $user_links_ids = array_column($user_links,'user_id_linked');

        // If logged user is account manager, then display jobs of agents linked to leads of account manager
        if($user->user_role == 1){
            for($i=0;$i<count($user_links_ids);$i++){
                $user_links = User_links::where('user_id',$user_links_ids[$i])->where('is_deleted',0)->select('user_id_linked')->get()->toArray();

                if(!empty($user_links)){
                    $user_links_id_array = array_column($user_links,'user_id_linked');
                    $user_links_ids = array_merge($user_links_ids,$user_links_id_array);
                }
            }
        }
        
        $user_links_ids = array_values(array_unique($user_links_ids));
        
        return $user_links_ids;
    }
    
    public static function getRepresentationAreaList(){
        /*return ['country'=>'Country','state'=>'State','district'=>'District','legislative_assembly_constituency'=>'Legislative Assembly Constituency',
        'parliamentary_constituency'=>'Parliamentary Constituency','municipal_corporation'=>'Municipal corporation (Mahanagar Palika)',
        'municipality'=>'Municipality (Nagar Palika)','city_council'=>'City Council (Nagar Panchayat)','block'=>'Block','ward'=>'Ward','sub_district'=>'Sub-district (Tehsil)',
        'village'=>'Village'];*/
        
        $rep_area_data = RepresentationAreaList::where('is_deleted',0)->get()->toArray();
        
        return $rep_area_data;
    }
    
}