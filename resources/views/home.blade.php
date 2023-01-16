@extends('layouts.front_main')
<?php use App\Helpers\CommonHelper; ?>

@section('content')
<div class="mainCenter">
    <div class="comtBox">
        <div class="d-flex align-items-center">
            <figure><img src="{{url('images/user.jpg')}}" class="img-fluid" onclick="window.location.href='{{url('user/profile')}}'" style="cursor:pointer;"></figure>
            <textarea name="" id="" cols="30" rows="10">Want to connect & submit suggestion/request?</textarea>
        </div>
        @if($user->user_role == 3)
            <a href="{{url('submission/subscriber/add')}}">Click Here</a>
        @endif
    </div>
    <div class="socialFeed">
        <ul class="nav nav-fill navtop">
            <li class="nav-item">
                <a class="nav-link active" href="#openSub" data-toggle="tab">All Submissions</a>
            </li>
            
            @if($user->user_role == 3)
                <li>/</li>
                <li class="nav-item">
                    <a class="nav-link" href="#yourSub" data-toggle="tab">Your Submissions</a>
                </li>
            @endif
            
            @if($user->user_role == 2)
                <li>/</li>
                <li class="nav-item">
                    <a class="nav-link" href="#pendingSub" data-toggle="tab">Pending</a>
                </li>
                <li>/</li>
                <li class="nav-item">
                    <a class="nav-link" href="#closedSub" data-toggle="tab">Closed</a>
                </li>
            @endif
        </ul>
        <hr>
        <div class="tab-content pt-0">
            <div class="tab-pane active" role="tabpanel" id="openSub">
                <?php displaySubmissions($submissions_all,$user); ?>
            </div>
            <div class="tab-pane" role="tabpanel" id="yourSub">
              <?php displaySubmissions($submissions_my,$user); ?>
            </div>
            <div class="tab-pane" role="tabpanel" id="pendingSub">
              <?php displaySubmissions($submissions_pending,$user); ?>
            </div>
            <div class="tab-pane" role="tabpanel" id="closedSub">
              <?php displaySubmissions($submissions_closed,$user); ?>
            </div>
        </div>
    </div>

</div>

<?php function displaySubmissions($submissions,$user){ ?>

@for($i=0;$i<count($submissions);$i++)
    <?php  $profile_url = ($submissions[$i]['user_id'] != $user->id)?url('user/profile/view/'.$submissions[$i]['user_id']):url('user/profile'); ?>
    <div>
    <p class="forTo">Submitted to <a href="{{url('subscriber/profile/view/'.$submissions[$i]['subscriber_id'])}}">{{$submissions[$i]['subscriber_name']}}</a> &nbsp;&nbsp;<span>{{CommonHelper::time_elapsed_string($submissions[$i]['created_at'])}}</span> </p>
    <div class="d-flex">
      <div class="userImage"><a href="{{$profile_url}}"><img src="{{$submissions[$i]['user_profile_image_url']}}" class="img-fluid"></a></div>
      <div class="userDetail position-relative">
        <div class="dropDown"><a href="#"><i class="fas fa-ellipsis-h"></i></a></div>
        <h4><a href="{{$profile_url}}" style="color:#2a3548">{{$submissions[$i]['name']}} <span>{{'@'}}{{$submissions[$i]['user_name']}}</span></a></h4>
        <p>{{$submissions[$i]['summary']}}</p>
        <div class="postImg">
            @if(stripos($submissions[$i]['file'],'.pdf') === false )
                <img src="{{$submissions[$i]['file_url']}}" class="img-fluid">
            @endif        
        </div>
        <div class="d-flex justify-content-between">
            <a href="{{url('submission/detail/'.$submissions[$i]['id'])}}">View details</a>
            <p>{{$submissions[$i]['submission_status_name']}} </p>
        </div>
       

      </div>
    </div>
    </div>
    <hr>
@endfor
<?php } ?>

@endsection
