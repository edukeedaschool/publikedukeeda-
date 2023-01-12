@extends('layouts.front_profile',['page_title'=>$title])

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="bg-white h-100 p-4">
            <div class="userPropic">
              <figure><img src="{{$subscriber_data['image_url']}}" class="img-thumbnail">
                <figcaption>{{$subscriber_data['subscriber_name']}}</figcaption>
              </figure>
              <ul class="mt-4 userEmail text-left">
                <li>Email Id:- {{$subscriber_data['email']}}</li>
                <li>Phone:- {{$subscriber_data['mobile_no']}}</li>
              </ul>
              <hr>
              <ul class="mt-4 following text-left">
                @if($user->user_role == 3)
                    <?php $css_class = ($subscriber_data['is_following'] == 1)?'following':''; ?>                                     
                    <?php $text = ($subscriber_data['is_following'] == 1)?'Unfollow':'Follow'; ?> 
                    <li><a href="javascript:;" onclick="followUnfollowSubscriber(this,{{$subscriber_data['subscriber_id']}});" class="foBtn {{$css_class}}">{{$text}}</a></li>
                @endif
                <li><a href="{{url('subscriber/followers/'.$subscriber_data['subscriber_id'])}}">Followers: {{$subscriber_data['followers_count']}}</a></li>
                <li><a href="{{url('user/message/create/'.$subscriber_data['user_id'])}}" class="foBtn"><i class="fas fa-envelope"></i> Message</a></li>
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
              <div class="w-50">: {{ucfirst($subscriber_data['gender'])}}</div>
            </div>
            <div class="d-flex pb-3">
              <div class="w-50">Key identity / Bio</div>
              <div class="w-50">: {{$subscriber_data['subscriber_bio']}} </div>
            </div>
            
            <div class="d-flex pb-3">
                <div class="w-50">DOB</div>
                <div class="w-50">: @if(!empty($subscriber_data['dob'])) {{date('d M Y',strtotime($subscriber_data['dob']))}} @endif</div>
            </div>

            @if($subscriber_data['group_type'] == 'political' && $subscriber_data['group_sub_type'] == 'person')
                <div class="d-flex pb-3">
                    <div class="w-50">Political Party</div>
                    <div class="w-50">: {{$subscriber_data['pol_party_name']}}</div>
                </div>
                <div class="d-flex pb-3">
                    <div class="w-50">Official position in party</div>
                    <div class="w-50">: {{$subscriber_data['pol_party_position_name']}}</div>
                </div>
                <div class="d-flex pb-3">
                    <div class="w-50">Elected official position</div>
                    <div class="w-50"> : {{$subscriber_data['elected_official_position_name']}}</div>
                </div>
            @endif
                
            @if($subscriber_data['group_type'] == 'non_political' && $subscriber_data['group_sub_type'] == 'person')
                <div class="d-flex pb-3">
                    <div class="w-50">Organisation name</div>
                    <div class="w-50"> : {{$subscriber_data['org_name']}}</div>
                </div>
            @endif
            
            <div class="d-flex pb-3">
                <div class="w-50">Key Identity</div>
                <div class="w-50"> : {{$subscriber_data['key_identity1']}}</div>
            </div>
            
            <hr>
            <h2 class="mt-3 justify-content-between d-flex">Address</h2>
            
            <div class="d-flex pb-3">
                <div class="w-50">{{$subscriber_data['org_name']}}</div>
                <div class="w-50"> :  {{$subscriber_data['address_line1']}}, {{$subscriber_data['district_name']}}, {{$subscriber_data['state_name']}}, {{$subscriber_data['postal_code']}}</div>
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
<script src="{{ asset('js/subscriber.js') }}" ></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#general_div .form-control").attr('readonly',true).css('background-color','#fff');
    })
</script>    
@endsection