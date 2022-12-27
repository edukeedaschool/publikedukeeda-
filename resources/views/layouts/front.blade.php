<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/developer.css?v=1.50') }}">
<script>var ROOT_PATH = "{{url('/')}}"; </script>
<title>Review Management System</title>
</head>
<body>
<!-- Start Header -->  
<header class="kiaasa_header">
    <div class="container-fluid">
        <div class="row align-items-md-center">
            <div class="col-md-4 logo">
                <a href="{{ url('/home') }}">Review Management System</a>
            </div> 
            <div class="col-md-4 text-center">
                <h2><?php if(!empty($title)){echo $title;}else{echo "";}?></h2>
            </div>
            <div class="col-md-4 kiaasa_header_right navbar">
                <ul class="navbar-nav ml-auto">
                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    @if(isset(Auth::user()->name))
                        
                        <!-- Nav Item - User Information -->

                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle pr-0" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fa fa-angle-down"></i>
                                <img class="img-profile rounded-circle" src="{{asset('images/proimg.jpg')}}" alt="" />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{url('home')}}"> 
                                    Dashboard
                                </a>
                                  <a class="dropdown-item" href="{{url('account/profile/edit')}}"> 
                                    Edit Profile 
                                </a>
                                  
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();"> 
                                  {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                            </div>
                        </li>
                    @endif
                </ul>
            </div> 
        </div>
    </div>
   
</header>

@yield('content')
<footer class="container-fluid"><div id="page_desc_div"></div></footer>
<script src="{{ asset('js/jquery-3.4.1.slim.min.js') }}" ></script>
<script	src="{{ asset('js/jquery-2.2.4.min.js') }}" ></script>
<script src="{{ asset('js/popper.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap.min.js') }}" ></script> 
<script src="{{ asset('js/common.js?v=1.65') }}" ></script> 
@yield('scripts')

</body>
</html>