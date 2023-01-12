@extends('layouts.front_main', ['page_title'=>$title])

@section('content')
<div class="mainCenter">
    <div class="choose">
        <div class="alert alert-success alert-dismissible elem-hidden"  id="listMessageSuccessMessage"></div>
        <div class="alert alert-danger alert-dismissible elem-hidden"  id="listMessageErrorMessage"></div>
        
        <form class="form-horizontal" action="" method="post" name="listMessageForm" id="listMessageForm">
            <h2 class="mt-3 justify-content-between d-flex">My Messages</h2>
            <table style="width:100%;">
                @for($i=0;$i<count($message_list);$i++)
                    <tr>
                        <td>
                            <a href="javascript:;" onclick="$('#msg_{{$message_list[$i]['id']}}').toggle();" style="color:#2a3548;"> 
                                <img src="{{$message_list[$i]['from_profile_image_url']}}" class="img-thumbnail" style="max-width:50px; "> {{$message_list[$i]['from_name']}}
                            </a>
                        </td>
                        <td>&raquo;</td>
                        <td> 
                            <a href="javascript:;" onclick="$('#msg_{{$message_list[$i]['id']}}').toggle();" style="color:#2a3548;">
                                <img src="{{$message_list[$i]['to_profile_image_url']}}" class="img-thumbnail" style="max-width:50px; "> {{$message_list[$i]['to_name']}}
                            </a>
                        </td>
                        <td> {{date('d-m-Y H:i',strtotime($message_list[$i]['created_at']))}}</td>
                    </tr>
                    <tr id="msg_{{$message_list[$i]['id']}}" style="display:none;">
                        <td colspan="5">
                            {!! nl2br($message_list[$i]['message']) !!}
                        </td> 
                    </tr>
                    <tr><td colspan="5"><hr style="border-width:1px;" /></td></tr>
                    <tr><td style="height:15px;">&nbsp;</td></tr>
                @endfor    
            </table>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" >
    $(document).ready(function(){
    });
</script>
<script src="{{ asset('js/users.js') }}" ></script>
@endsection

