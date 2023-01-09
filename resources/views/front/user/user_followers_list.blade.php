@extends('layouts.front_main', ['display_banner' => 'no','page_title'=>$title])

@section('content')
<div class="mainCenter">
    <div class="choose">
        
        <h2>Followers</h2>
        <div class="follingList">
        @for($i=0;$i<count($user_followers);$i++)
            <div class="followUser">
              <div> 
                <a href="">
                  <img src="{{$user_followers[$i]['image_url']}}" class="img-thumbnail"> {{$user_followers[$i]['name']}}
                  <div class="followDetail">
                    <div class="followName"><img src="{{$user_followers[$i]['image_url']}}"> {{$user_followers[$i]['name']}} <span>{{'@'}}{{$user_followers[$i]['user_name']}}</span></div>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                    <ul>
                      <li>District <span>{{$user_followers[$i]['district_name']}}</span></li>
                      <li>State <span>{{$user_followers[$i]['state_name']}}</span></li>
                      <li>Country <span>{{$user_followers[$i]['country_name']}}</span></li>
                    </ul>
                  </div>
               </a> 
              </div>
                <?php $css_class = ($user_followers[$i]['following'] == 1)?'following':''; ?>                                     
                <?php $text = ($user_followers[$i]['following'] == 1)?'Unfollow':'Follow'; ?> 
                <a href="javascript:;" onclick="followUnfollowUser(this,{{$user_followers[$i]['follower_id']}});" class="ml-auto followBtn {{$css_class}}" >{{$text}}</a>
            </div>
        @endfor
        </div>
        
        
        <h2>Following</h2>
        <div class="follingList">
        @for($i=0;$i<count($user_following_users);$i++)
            <div class="followUser">
              <div> 
                <a href="">
                  <img src="{{$user_following_users[$i]['image_url']}}" class="img-thumbnail"> {{$user_following_users[$i]['name']}}
                  <div class="followDetail">
                    <div class="followName"><img src="{{$user_following_users[$i]['image_url']}}"> {{$user_following_users[$i]['name']}} <span>{{'@'}}{{$user_following_users[$i]['user_name']}}</span></div>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                    <ul>
                      <li>District <span>{{$user_following_users[$i]['district_name']}}</span></li>
                      <li>State <span>{{$user_following_users[$i]['state_name']}}</span></li>
                      <li>Country <span>{{$user_following_users[$i]['country_name']}}</span></li>
                    </ul>
                  </div>
               </a> 
              </div>
                <?php $css_class = ($user_following_users[$i]['following'] == 1)?'following':''; ?>                                     
                <?php $text = ($user_following_users[$i]['following'] == 1)?'Unfollow':'Follow'; ?> 
                <a href="javascript:;" onclick="followUnfollowUser(this,{{$user_following_users[$i]['user_id']}});" class="ml-auto followBtn {{$css_class}}" >{{$text}}</a>
            </div>
        @endfor
        
        
        @for($i=0;$i<count($user_following_subscribers);$i++)
            <div class="followUser">
              <div> 
                <a href="">
                  <img src="{{$user_following_subscribers[$i]['image_url']}}" class="img-thumbnail"> {{$user_following_subscribers[$i]['name']}}
                  <div class="followDetail">
                    <div class="followName"><img src="{{$user_following_subscribers[$i]['image_url']}}"> {{$user_following_subscribers[$i]['name']}} <span>{{'@'}}{{$user_following_subscribers[$i]['user_name']}}</span></div>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                    <ul>
                      <li>District <span>{{$user_following_subscribers[$i]['district_name']}}</span></li>
                      <li>State <span>{{$user_following_subscribers[$i]['state_name']}}</span></li>
                      <li>Country <span>{{$user_following_subscribers[$i]['country_name']}}</span></li>
                    </ul>
                  </div>
               </a> 
              </div>
                <?php $css_class = ($user_following_subscribers[$i]['following'] == 1)?'following':''; ?>                                     
                <?php $text = ($user_following_subscribers[$i]['following'] == 1)?'Unfollow':'Follow'; ?> 
                <a href="javascript:;" onclick="followUnfollowSubscriber(this,{{$user_following_subscribers[$i]['subscriber_id']}});" class="ml-auto followBtn {{$css_class}}">{{$text}}</a> 
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

