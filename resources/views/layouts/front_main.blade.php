<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- favicon icon -->
  <link rel="icon" href="images/favicon.ico">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Title of website -->
  <title>@if(isset($page_title)) {{$page_title}} @endif</title>
  <!-- Bootstrap core CSS -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" >
  <!-- Custom Fonts CSS -->
  <link href="{{ asset('css/fonts.css') }}" rel="stylesheet" type="text/css">
  <!-- slider CSS -->
  <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/owl.theme.default.min.css') }}" rel="stylesheet" type="text/css">
  <!-- Custom CSS -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/developer-front.css') }}" rel="stylesheet" type="text/css">
  <script>var ROOT_PATH = "{{url('/')}}"; </script>
</head>

<?php $user_data = Auth::user(); ?>
<?php  use App\Helpers\CommonHelper; ?>
<?php 
    $headers = CommonHelper::getAPIHeaders();
    $url = url('/api/user/teams/'.$user_data->id);
    $response = CommonHelper::processCURLRequest($url,'','','',$headers);//print_r($response);//exit;
    $response = json_decode($response,true);
    $user_teams = isset($response['users_teams'])?$response['users_teams']:[];
?>
<body>
      <header class="d-flex align-items-center">
    <div class="container">
      <div class="row align-items-center justify-content-between">
        <div class="col-auto">
          <div class="logo"><a href="{{url('/')}}">Logo Here</a></div>
        </div>
        <div class="col-auto">
          <ul class="bellMsg">
            <li><a href="javascript:;"><i class="fas fa-envelope"></i></a></li>
            <li><a href="javascript:;"><i class="fas fa-bell"></i></a></li>
            <li class="d-lg-none d-block"><a href="javascript:;" class="button-left"><i class="fas fa-bars"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>
  <section class="mainSection">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-3">
          <div class="leftSidebar left">
          
            <ul class="sidebarMenu">
                <li><a href="{{url('/')}}"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="javascript:;"><i class="fas fa-bell"></i> Notification</a></li>
                <li><a href="javascript:;"><i class="fas fa-envelope"></i> Message</a></li>
                <li><a href="javascript:;"><i class="fas fa-file-alt"></i> Your submissions</a></li>
                <li><a href="{{url('user/profile')}}"><i class="fas fa-user"></i> profile</a></li>
                @for($i=0;$i<count($user_teams);$i++)
                    <li><a href="{{url('team/members/'.$user_teams[$i]['subscriber_id'])}}"><i class="fas fa-users"></i> Team {{$user_teams[$i]['subscriber_name']}}</a></li>
                @endfor    
                <li><a href="javascript:;"><i class="fas fa-star"></i> Submissions for review</a></li>
                <li><a href="{{url('groups/list')}}"><i class="fas fa-search"></i> Search Group</a></li>
                @if(isset($user_data->id) && !empty($user_data->id) )
                    @if($user_data->user_role == 1)
                        <li><a href="{{url('states/list')}}" ><i class="fas fa-users"></i> Admin Panel</a></li>
                    @endif
                    @if($user_data->user_role == 2)
                        <li><a href="{{url('subscriber/review-data/view')}}" ><i class="fas fa-users"></i> Subscriber Panel</a></li>
                    @endif
                    <li><a href="javascript:;" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                @else
                    <li><a href="{{url('user/login')}}" ><i class="fas fa-sign-out-alt"></i> Login</a></li>
                @endif
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            
            @if(isset($user_data->id) && !empty($user_data->id))
                <div class="userName">
                    @if($user_data->user_role == 2)<a href="{{url('subscriber/profile/view/'.$user_data->subscriber_id)}}"> @else <a href="{{url('user/profile/view/'.$user_data->id)}}"> @endif
                    @if(isset($user_data->image) && !empty($user_data->image))  
                        <figure><img src="{{url('images/user_images/'.$user_data->image)}}" class="img-fluid"></figure>
                    @else
                        <figure><img src="{{url('images/user.jpg')}}" class="img-fluid"></figure>
                    @endif
                    <div>
                      <h3>{{$user_data->name}}</h3>
                      <p>{{$user_data->user_name}}</p>
                    </div>
                  </a>
                </div>
            @endif
        </div>
        </div>
        
        @if(isset($display_banner) && $display_banner == 'no')  
            <div class="col-lg-9">
                @yield('content')
            </div>
        @else
            <div class="col-lg-6">
                @yield('content')
            </div>
            <div class="col-md-3 d-none d-lg-block">
                <div><img src="{{url('images/banner.jpg')}}" class="img-fluid"></div>
                <div class="mt-3"><img src="{{url('images/banner2.jpg')}}" class="img-fluid"></div>
            </div>
        @endif
        
          
      </div>
    </div>
  </section>
    
  <!-- jQuery library -->
  <script src="{{ asset('js/jquery.min.js') }}" type="text/javascript"></script>
  <!-- Bootstrap core JS -->
  <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('js/popper.min.js') }}" type="text/javascript"></script>
  <!-- jQuery for menu -->
  <?php /* ?><script src="{{ asset('js/menu.js') }}" type="text/javascript" ></script> <?php */ ?>
  <script src="{{ asset('js/common.js?v=1.65') }}" ></script> 
  <script type="text/javascript">
    /*jQuery(document).ready(function ($) {
      jQuery('.siteMenus').stellarNav({
        breakpoint: 767,
        position: 'right',
      });
    });*/
  </script>
  <!-- slider Top -->
<script>$(document).ready(function(){
    $('.button-left').click(function(){
        $('.leftSidebar').toggleClass('fliph');
    });
      
 });</script> 
 <script>
  $(window).scroll(function(){
    if ($(window).scrollTop() >= 1) {
        $('header').addClass('fixed-header');
        // $('header').addClass('visible-title');
    }
    else {
        $('header').removeClass('fixed-header');
        // $('header').removeClass('visible-title');
    }
});

 </script> 
 @yield('scripts')       
</body>

</html>    


