@extends('layouts.front_profile',['page_title'=>$title])

@section('content')

<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-3">
        <div class="bg-white h-100 p-4">
        <div class="userPropic">
          <figure><img src="{{$user_profile['image_url']}}" class="img-thumbnail">
            <figcaption>{{$user_profile['name']}}</figcaption>
          </figure>
          <ul class="mt-4 userEmail text-left">
            <li>Email Id:- {{$user_profile['email']}}</li>
            <li>Phone:- {{$user_profile['mobile_no']}}</li>
          </ul>
          <hr>
          <ul class="mt-4 following text-left">
            <li><a href="" class="foBtn">Follow</a></li>
            <li><a href="">Following 15</a></li>
            <li><a href="">Followers 250</a></li>
          </ul>

        </div>
      </div>
      </div>
      <div class="col-lg-6">
        <div class="mainCenter">
          <div class="choose">
            <h2 class="mt-3 justify-content-between d-flex">General Information</h2>
            <div class="d-flex pb-3">
              <div class="w-50">Gender</div>
              <div class="w-50">: {{ucfirst($user_profile['gender'])}}</div>
            </div>
            <div class="d-flex pb-3">
              <div class="w-50">Key identity / Bio</div>
              <div class="w-50">: </div>
            </div>
            <div class="d-flex pb-3">
              <div class="w-50">Profession</div>
              <div class="w-50">: {{ucfirst($user_profile['profession'])}}</div>
            </div>
            <div class="d-flex pb-3">
              <div class="w-50">DOB</div>
              <div class="w-50">: @if(!empty($user_profile['dob'])) {{date('d M Y',strtotime($user_profile['dob']))}} @endif</div>
            </div>

            <div class="d-flex pb-3">
              <div class="w-50">Place</div>
              <div class="w-50">: {{$user_profile['district_name']}}, {{$user_profile['state_name']}}, {{$user_profile['country_name']}}</div>
            </div>
            <?php if($user_profile['qualification'] == 'pursing_graduate') { $qualification = 'Pursuing '.$user_profile['course_name'].' / Expected year '.$user_profile['degree_year']; }
              elseif(in_array($user_profile['qualification'], ['pursing_graduate','graduate','post_graduate','doctorate'])) { $qualification = $user_profile['course_name'].' / '.date('Y',strtotime($user_profile['degree_year'])).' passout'; }
              else{ $qualification = $user_profile['qualification']; }
              ?>
            <div class="d-flex pb-3">
              <div class="w-50">Qualification</div>
              <div class="w-50">: {{$qualification}}</div>
            </div>
            <div class="d-flex">
              <div class="w-50">List of Submissions</div>
              <div class="w-50"> <a href="#">: Click here</a></div>
            </div>
          </div>
        </div>

      </div>
      <div class="col-md-3 d-none d-lg-block">
          @include('front.right_banner') 
      </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="{{ asset('js/front_data.js') }}" ></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#general_div .form-control").attr('readonly',true).css('background-color','#fff');
    })
</script>    
@endsection