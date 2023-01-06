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
            @yield('content')
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


