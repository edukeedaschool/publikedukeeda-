@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="groupsContainer">
                <form class="" name="editSubGroupFrm" id="editSubGroupFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editSubGroupSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editSubGroupErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Sub Group Name</label>
                            <input id="subGroupName" type="text" class="form-control" name="subGroupName" value="{{$sub_group_data->sub_group_name}}" >
                            <div class="invalid-feedback" id="error_validation_subGroupName"></div>
                            <input id="sub_group_id" type="hidden" name="sub_group_id" value="{{$sub_group_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Group</label>
                            <select id="groupId" class="form-control" name="groupId" >
                                <option value="">Group</option>
                                @for($i=0;$i<count($group_list);$i++)
                                     <?php $sel = ($group_list[$i]['id'] == $sub_group_data->id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$group_list[$i]['id']}}">{{$group_list[$i]['group_name']}}</option>
                                @endfor  
                            </select>   
                            <div class="invalid-feedback" id="error_validation_groupId"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="subGroupStatus" class="form-control" name="subGroupStatus">
                                <option value="">Status</option>
                                <option value="1" @if($sub_group_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($sub_group_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_subGroupStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            
                           <button type="button" id="sub_group_edit_cancel" name="sub_group_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('sub-group/list')}}'">Cancel</button>
                           <button type="button" id ="sub_group_edit_submit" name="sub_group_edit_submit" class="btn btn-dialog" onclick="submitEditSubGroup();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>

@endsection
