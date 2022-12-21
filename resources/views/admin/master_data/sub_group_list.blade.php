@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >
           <div class="separator-10">&nbsp;</div>
           
            <form method="get">
                <div class="row" >
                    <div class="col-6"></div>
                    <div class="col-6">
                        <div class="row justify-content-end">
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Sub Group" class="btn btn-dialog" onclick="location.href='{{ url('/sub-group/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="sub_GroupUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="sub_GroupUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="sub_Group_id_chk_all" id="sub_Group_id_chk_all" onclick="checkAllCheckboxes(this,'sub_Group-id')"> ID</th>
                            <th>Sub Group Name</th>
                            <th>Group</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($sub_group_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="sub_Group_id_chk" id="sub_Group_id_chk" value="{{$sub_group_list[$i]['id']}}" class="sub_Group-id-chk"> {{$sub_group_list[$i]['id']}}</td>
                                    <td>{{$sub_group_list[$i]['sub_group_name']}}</td>
                                    <td>{{$sub_group_list[$i]['group_name']}}</td>
                                    <td>{{($sub_group_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('sub-group/edit/'.$sub_group_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Sub Group" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $sub_group_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$sub_group_list->count()}} of {{ $sub_group_list->total() }} Sub Groups.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="sub_Group_delete_submit" name="sub_Group_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','sub_Group');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="sub_Group_enable_submit" name="sub_Group_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','sub_Group');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="sub_Group_disable_submit" name="sub_Group_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','sub_Group');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
