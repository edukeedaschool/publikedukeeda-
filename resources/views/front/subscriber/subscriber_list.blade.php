@extends('layouts.front_main', ['display_banner' => 'no','page_title'=>$title])

@section('content')
<div class="mainCenter">
    <div class="choose">
        <div class="alert alert-success alert-dismissible elem-hidden"  id="followSuccessMessage"></div>
        <div class="alert alert-danger alert-dismissible elem-hidden"  id="followErrorMessage"></div>
        <form method="GET">
            <div class="row align-items-center">
              <div class="col-md-2"><h2>All Groups</h2></div>
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <input id="name" name="name" type="text" placeholder="Search by name" class="form-control" value="{{request('name')}}">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <input id="district" name="district" type="text" placeholder="Search by district" class="form-control" value="{{request('district')}}">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group mb-0">
                  <input id="search" name="search" type="submit" class="btn btn-primary" value="Search">
                </div>
              </div>
            </div>
        </form>

        <div class="brd"></div>
        <h4 class="orTitle">Person</h4>
        <ul class="personList">
            @for($i=0;$i<count($subscribers_person);$i++)
                <?php $css_class = ($subscribers_person[$i]['following'] == 1)?'following':''; ?>                                     
                <?php $text = ($subscribers_person[$i]['following'] == 1)?'Unfollow':'Follow'; ?>           
                <li>
                  <div class="d-flex align-items-center">
                    <div> <a href="{{url('subscriber/profile/view/'.$subscribers_person[$i]['subscriber_id'])}}">{{$subscribers_person[$i]['subscriber_name']}}</a>, {{$subscribers_person[$i]['subscriber_bio']}} </div> 
                    <a href="javascript:;" onclick="followUnfollowSubscriber(this,{{$subscribers_person[$i]['subscriber_id']}});" class="ml-auto flwBtn {{$css_class}}">{{$text}}</a> 
                  </div>
                  <span><i class="fas fa-map-marker-alt"></i> {{$subscribers_person[$i]['district_name']}}, {{$subscribers_person[$i]['state_name']}}</span>
                </li>
            @endfor
        </ul>

        <div class="mt-5">
            <h4 class="orTitle">Organisation</h4>
            <ul class="personList">
                @for($i=0;$i<count($subscribers_org);$i++)
                    <?php $css_class = ($subscribers_org[$i]['following'] == 1)?'following':''; ?>         
                    <?php $text = ($subscribers_org[$i]['following'] == 1)?'Unfollow':'Follow'; ?>               
                    <li>
                      <div class="d-flex align-items-center">
                        <div > <a href="{{url('subscriber/profile/view/'.$subscribers_org[$i]['subscriber_id'])}}">{{$subscribers_org[$i]['subscriber_name']}}</a>, {{$subscribers_org[$i]['subscriber_bio']}} </div> 
                        <a href="javascript:;" onclick="followUnfollowSubscriber(this,{{$subscribers_org[$i]['subscriber_id']}});" class="ml-auto flwBtn {{$css_class}}">{{$text}}</a> 
                      </div>
                      <span><i class="fas fa-map-marker-alt"></i> {{$subscribers_org[$i]['district_name']}}, {{$subscribers_org[$i]['state_name']}}</span>
                    </li>
                @endfor
            </ul>
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
@endsection

