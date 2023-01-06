@extends('layouts.front_main', ['display_banner' => 'no','page_title'=>$title])

@section('content')
<div class="mainCenter">
<div class="choose">
  <h2>Team {{$subscriber_data['subscriber_name']}} </h2>

@foreach($team_members_list as $designation_id=>$members_list)
    <h4 class="orTitle">{{$designation_list[$designation_id]}}</h4> 
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Representation Area</th>
            <th scope="col">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
            @for($i=0;$i<count($members_list);$i++)
                <tr>
                  <td><a href="{{url('user/profile/view/'.$members_list[$i]['team_member_id'])}}">{{$members_list[$i]['team_member_name']}}</a></td>
                  <td>{{$members_list[$i]['representation_area']}}</td>
                  <td align="center" class="callIcon">
                    <a href="#"><i class="fas fa-envelope"></i></a> 
                    <a href="#"><i class="fas fa-video"></i></a> 
                    <a href="#"><i class="fas fa-phone-square-alt"></i></a> 
                  </td>
                </tr>
            @endfor
          
        </tbody>
      </table>
    </div>
@endforeach
  
</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" >
    $(document).ready(function(){
       
    });
</script>
<script src="{{ asset('js/submission.js') }}" ></script>
@endsection

