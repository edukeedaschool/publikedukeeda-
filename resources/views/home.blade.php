@extends('layouts.front_main')

@section('content')
<div class="mainCenter">
    <div class="comtBox">
      <div class="d-flex align-items-center">
        <figure><img src="{{url('images/user.jpg')}}" class="img-fluid"></figure>
        <textarea name="" id="" cols="30" rows="10">Want to connect & submit suggestion/request?</textarea>
      </div>
      <a href="">Click Here</a>
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
          <div>
            <p class="forTo">Forward to <a href="">Erling Haaland </a> <span>5 hrs ago</span> </p>
            <div class="d-flex">
              <div class="userImage"><img src="{{url('images/user.jpg')}}" class="img-fluid"></div>
              <div class="userDetail position-relative">
                <div class="dropDown"><a href=""><i class="fas fa-ellipsis-h"></i></a></div>
                <h4>CORE (Satoshi) <span>@Core_002·12h</span></h4>
                <p>The step before experiencing the most gratifying sleep of your life - get yourself Pro series
                  mattresses...</p>
                <div class="postImg"><img src="{{url('images/post-img.jpg')}}" class="img-fluid"></div>
                <div class="d-flex justify-content-between">
                  <a href="">View details</a>
                  <p>Under review OR Review Completed OR Case closed</p>
                </div>
                <hr>
                <ul class="shareList">
                  <li><a href=""><i class="fas fa-comments"></i> <span>30</span></a></li>
                  <li><a href=""><i class="fas fa-thumbs-up"></i> <span>50</span></a></li>
                  <li><a href=""><i class="fas fa-share"></i></a></li>
                </ul>

              </div>
            </div>
          </div>
          <hr>
          <div>
            <p class="forTo">Forward to <a href="">Erling Haaland </a> <span>5 hrs ago</span> </p>
            <div class="d-flex">
              <div class="userImage"><img src="{{url('images/user.jpg')}}" class="img-fluid"></div>
              <div class="userDetail position-relative">
                <div class="dropDown"><a href=""><i class="fas fa-ellipsis-h"></i></a></div>
                <h4>CORE (Satoshi) <span>@Core_002·12h</span></h4>
                <p>The step before experiencing the most gratifying sleep of your life - get yourself Pro series
                  mattresses...</p>
                <div class="postImg"><img src="{{url('images/post-img2.jpg')}}" class="img-fluid"></div>
                <div class="d-flex justify-content-between">
                  <a href="">View details</a>
                  <p>Under review OR Review Completed OR Case closed</p>
                </div>
                <hr>
                <ul class="shareList">
                  <li><a href=""><i class="fas fa-comments"></i> <span>30</span></a></li>
                  <li><a href=""><i class="fas fa-thumbs-up"></i> <span>50</span></a></li>
                  <li><a href=""><i class="fas fa-share"></i></a></li>
                </ul>

              </div>
            </div>
          </div>
          <hr>
          <div>
            <p class="forTo">Forward to <a href="">Erling Haaland </a> <span>5 hrs ago</span> </p>
            <div class="d-flex">
              <div class="userImage"><img src="{{url('images/user.jpg')}}" class="img-fluid"></div>
              <div class="userDetail position-relative">
                <div class="dropDown"><a href=""><i class="fas fa-ellipsis-h"></i></a></div>
                <h4>CORE (Satoshi) <span>@Core_002·12h</span></h4>
                <p>The step before experiencing the most gratifying sleep of your life - get yourself Pro series
                  mattresses...</p>
                <div class="postImg"><img src="{{url('images/post-img3.jpg')}}" class="img-fluid"></div>
                <div class="d-flex justify-content-between">
                  <a href="">View details</a>
                  <p>Under review OR Review Completed OR Case closed</p>
                </div>
                <hr>
                <ul class="shareList">
                  <li><a href=""><i class="fas fa-comments"></i> <span>30</span></a></li>
                  <li><a href=""><i class="fas fa-thumbs-up"></i> <span>50</span></a></li>
                  <li><a href=""><i class="fas fa-share"></i></a></li>
                </ul>

              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" role="tabpanel" id="yourSub">
          saf
        </div>

      </div>
    </div>

</div>

<?php function z1(){ ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top:20px; ">
                <div class="card-header">{{ __('Home Page') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @if(isset($user->id) && !empty($user->id) )
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> {{ __('Logout') }}</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else 
                        <a href="{{url('user/login')}}">Login</a> | <a href="{{url('user/signup')}}">Signup</a>
                    @endif

                    <?php /* ?>
                    @if($user->user_role == 2)
                        <a href="{{url('subscriber/review-data/view')}}">Review Range Data</a>
                    @endif <?php */ ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
@endsection
