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
  <script>var ROOT_PATH = "{{url('/')}}"; </script>
</head>
<?php $user_data = Auth::user(); ?>
<body class="loginBg">
    @if(isset($user_data->id))
        <header class="d-flex align-items-center">
            <div class="container">
              <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                      <div class="logo">Logo Here</div>
                    </div>
                    <div class="col-auto">
                      <ul class="bellMsg">
                            <li><a href=""><i class="fas fa-envelope"></i></a></li>
                            <li><a href=""><i class="fas fa-bell"></i></a></li>
                            <li class="d-lg-none d-block"><a href="#" class="button-left"><i class="fas fa-bars"></i></a></li>
                      </ul>
                    </div>
              </div>
            </div>
        </header>
    @endif
    
    <section class="loginPage">
        <div class="container">
            <div class="d-flex justify-content-center loginBox">
                <div class="user_card">
                    <div class="d-flex justify-content-center">
                        <div class="brand_logo_container">
                            <div style="height: 150px;width: 150px;border-radius: 50%;border: 2px solid white; background:rgb(219, 72, 72);color:#fff;line-height:150px;font-size:30px;">Logo</div>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
	</div>
    </section>
  <!-- jQuery library -->
  <script src="{{ asset('js/jquery.min.js') }}" type="text/javascript"></script>
  <!-- Bootstrap core JS -->
  <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('js/popper.min.js') }}" type="text/javascript"></script>
  <!-- jQuery for menu -->
  <script src="{{ asset('js/menu.js') }}" type="text/javascript" ></script>
  <script type="text/javascript">
    /*jQuery(document).ready(function ($) {
      jQuery('.siteMenus').stellarNav({
        breakpoint: 767,
        position: 'right',
      });
    });*/
  </script>
  <!-- slider Top -->
    <script>
    $(document).ready(function(){
        $('.button-left').click(function(){
            $('.leftSidebar').toggleClass('fliph');
        });
    });
    </script> 
 @yield('scripts')       
</body>

</html>    


