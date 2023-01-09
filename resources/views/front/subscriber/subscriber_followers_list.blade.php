@extends('layouts.front_main', ['display_banner' => 'no','page_title'=>$title])

@section('content')
<div class="mainCenter">
    <div class="choose">
        <div class="alert alert-success alert-dismissible elem-hidden"  id="followSuccessMessage"></div>
        <div class="alert alert-danger alert-dismissible elem-hidden"  id="followErrorMessage"></div>
        <h2>Followers</h2>
        <div class="follingList">
        @for($i=0;$i<count($subscriber_followers);$i++)
            <div class="followUser">
              <div> 
                <a href="">
                  <img src="{{$subscriber_followers[$i]['image_url']}}" class="img-thumbnail"> {{$subscriber_followers[$i]['name']}}
                  <div class="followDetail">
                    <div class="followName"><img src="{{$subscriber_followers[$i]['image_url']}}"> {{$subscriber_followers[$i]['name']}} <span>{{'@'}}{{$subscriber_followers[$i]['user_name']}}</span></div>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                    <ul>
                      <li>District <span>{{$subscriber_followers[$i]['district_name']}}</span></li>
                      <li>State <span>{{$subscriber_followers[$i]['state_name']}}</span></li>
                      <li>Country <span>{{$subscriber_followers[$i]['country_name']}}</span></li>
                    </ul>
                  </div>
               </a> 
              </div>
                <?php $css_class = ($subscriber_followers[$i]['following'] == 1)?'following':''; ?>                                     
                <?php $text = ($subscriber_followers[$i]['following'] == 1)?'Unfollow':'Follow'; ?> 
              <a href="javascript:;" onclick="followUnfollowUser(this,{{$subscriber_followers[$i]['follower_id']}});" class="ml-auto followBtn {{$css_class}}" >{{$text}}</a>
            </div>
        @endfor

      </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" >
    $(document).ready(function(){
    });
</script>
<script src="{{ asset('js/subscriber.js') }}" ></script>
<script src="{{ asset('js/users.js') }}" ></script>
@endsection

