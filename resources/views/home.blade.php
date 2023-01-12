@extends('layouts.front_main')

@section('content')
<div class="mainCenter">
    <div class="comtBox">
        <div class="d-flex align-items-center">
            <figure><img src="{{url('images/user.jpg')}}" class="img-fluid"></figure>
            <textarea name="" id="" cols="30" rows="10">Want to connect & submit suggestion/request?</textarea>
        </div>
        @if($user->user_role == 3)
            <a href="{{url('submission/subscriber/add')}}">Click Here</a>
        @endif
    </div>
    <div class="socialFeed">
      <ul class="nav nav-fill navtop">
        <li class="nav-item">
          <a class="nav-link active" href="#openSub" data-toggle="tab">Open Submissions</a>
        </li>
        <li>/</li>
        <li class="nav-item">
          <a class="nav-link" href="#yourSub" data-toggle="tab">Your Submissions</a>
        </li>
      </ul>
      <hr>
      <div class="tab-content pt-0">
        <div class="tab-pane active" role="tabpanel" id="openSub">
            @for($i=0;$i<count($submissions);$i++)
                <?php  $profile_url = url('user/profile/view/'.$submissions[$i]['user_id']); ?>
                <div>
                <!--<p class="forTo">Forward to <a href="">Erling Haaland </a> <span>5 hrs ago</span> </p>-->
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
                    <!--<hr>
                    <ul class="shareList">
                      <li><a href=""><i class="fas fa-comments"></i> <span>30</span></a></li>
                      <li><a href=""><i class="fas fa-thumbs-up"></i> <span>50</span></a></li>
                      <li><a href=""><i class="fas fa-share"></i></a></li>
                    </ul>-->

                  </div>
                </div>
                </div>
                <hr>
            @endfor
          
          
        </div>
        <div class="tab-pane" role="tabpanel" id="yourSub">
          saf
        </div>

      </div>
    </div>

</div>


@endsection
