@extends('layouts.front_main')

@section('content')
<div class="mainCenter">
    <div class="choose">
      <h2>Want to connect someone with your submission?</h2>
        <form class="form-horizontal" action="" method="post">
         <!-- Name input-->
          <div class="form-group">
            <label class="control-label" for="name"><b>Choose one</b></label>
            <select class="form-control" id="sub_group_id" required="true" name="sub_group_id" onchange="getSubmissionSubscribersList(this.value,'{{$user->id}}');">
                <option value="">Subgroup 1</option>
                @for($i=0;$i<count($sub_group_list);$i++)
                    <option value="{{$sub_group_list[$i]['id']}}">{{$sub_group_list[$i]['sub_group_name']}}</option>
                @endfor    
            </select>
            <div class="invalid-feedback" id="error_validation_sub_group_id"></div>
          </div>

          <!-- Email input-->
          <!--<div class="form-group">
            <label class="control-label mt-3" for="email"><b>Choose place</b></label>
            <select class="form-control" id="country" required="true" name="country">
              <option value="">From your district "district name"</option>
              <option value="">Not from your district but From your state "State Name" </option>
              <option value="">From other states of India </option>
            </select>
          </div>-->

          <!-- Message body -->
          <div class="form-group">
            <label class="control-label mt-3" for="message"><b>Select name</b></label>
            <select class="form-control" id="subscriber_id" required="true" name="subscriber_id">
              <option value="">Subscriber</option>
            </select>
            <div class="invalid-feedback" id="error_validation_subscriber_id"></div>
        </div>

          <!-- Form actions -->
          <div class="form-group">
            <div class="text-right">
              <button type="submit" class="btn">Next</button>
            </div>
          </div>
        </form>
    </div>
</div>


@endsection
