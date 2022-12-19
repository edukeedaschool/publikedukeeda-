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

@if(isset(Auth::user()->name))
<div class="bs-example" style="text-align: center;">
    <div class="btn-group">
        <!--<div class="btn-group">
            <button type="button" class="btn btn-dialog dropdown-toggle" data-toggle="dropdown">Users</button>
            <div class="dropdown-menu">
                <a href="{{url('user/list')}}" class="dropdown-item">User List</a>
            </div>
        </div>-->
        <div class="btn-group">
            <button type="button" class="btn btn-dialog dropdown-toggle" data-toggle="dropdown">Master Data</button>
            <div class="dropdown-menu">
                <a href="{{url('states/list')}}" class="dropdown-item">States</a>
                <a href="{{url('districts/list')}}" class="dropdown-item">Districts</a>
                <a href="{{url('mc1/list')}}" class="dropdown-item">Municipal Corporation (Mahanagar Palika)</a>
                <a href="{{url('mc2/list')}}" class="dropdown-item">Municipal Corporation (Nagar Palika)</a>
                <a href="{{url('city-council/list')}}" class="dropdown-item">City Council</a>
                <a href="{{url('block/list')}}" class="dropdown-item">Blocks</a>
                <a href="{{url('sub-district/list')}}" class="dropdown-item">Sub Districts</a>
                <a href="{{url('ward/list')}}" class="dropdown-item">Wards</a>
                <a href="{{url('village/list')}}" class="dropdown-item">Villages</a>
                <a href="{{url('postal-code/list')}}" class="dropdown-item">Postal Codes</a>
                <a href="{{url('political-party/list')}}" class="dropdown-item">Political Party</a>
                <a href="{{url('la-constituency/list')}}" class="dropdown-item">Legislative Assembly Constituency</a>
                <a href="{{url('pa-constituency/list')}}" class="dropdown-item">Parliamentary Constituency</a>
                <a href="{{url('elected-official-position/list')}}" class="dropdown-item">Elected Representative Official Position</a>
                <a href="{{url('political-party-official-position/list')}}" class="dropdown-item">Political Party Official Position</a>
                <a href="{{url('govt-department/list')}}" class="dropdown-item">Government Department</a>
                <a href="{{url('non-profit-organization/list')}}" class="dropdown-item">Non Profit Organization</a>
                <a href="{{url('group/list')}}" class="dropdown-item">Groups</a>
                <a href="{{url('sub-group/list')}}" class="dropdown-item">Sub Groups</a>
                <a href="{{url('submission-purpose/list')}}" class="dropdown-item">Submission Purpose</a>
                <a href="{{url('submission-type/list')}}" class="dropdown-item">Submission Type</a>
                <a href="{{url('review-level/list')}}" class="dropdown-item">Review Level</a>
            </div>
        </div>
        <div class="btn-group" style="margin-left:0px; ">
            <button type="button" class="btn btn-dialog dropdown-toggle" data-toggle="dropdown">Subscribers</button>
            <div class="dropdown-menu">
                <a href="{{url('subscriber/list')}}" class="dropdown-item">Subscribers</a>
            </div>
        </div>
        <div class="btn-group" style="margin-left:0px; ">
            <button type="button" class="btn btn-dialog dropdown-toggle" data-toggle="dropdown">Users</button>
            <div class="dropdown-menu">
                <a href="{{url('user/list')}}" class="dropdown-item">Users</a>
            </div>
        </div>
    </div>
</div>
@endif

@yield('content')
<footer class="container-fluid"><div id="page_desc_div"></div></footer>
<script src="{{ asset('js/jquery-3.4.1.slim.min.js') }}" ></script>
<script	src="{{ asset('js/jquery-2.2.4.min.js') }}" ></script>
<script src="{{ asset('js/popper.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap.min.js') }}" ></script> 
<script src="{{ asset('js/common.js?v=1.65') }}" ></script> 
@yield('scripts')
@if(isset($page_name) && !empty($page_name)) <script type="text/javascript" >$(document).ready(function(){ displayPageDescription("{{$page_name}}"); });</script> @endif

<div class="modal fade content-dialog" id="image_common_dialog" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered" role="document" >
        <div class="modal-content">
            <div class="modal-header" >
                <h5 class="modal-title" id="image_common_dialog_title" >Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('images/close.png')}}" alt="Close" title="Close" /></button>
            </div>
            <div class="modal-body" id="image_common_dialog_content"></div>
            <div class="modal-footer center-footer">
                <button type="button" id="image_common_dialog_close" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>