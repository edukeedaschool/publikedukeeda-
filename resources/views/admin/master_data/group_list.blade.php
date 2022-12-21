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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Group" class="btn btn-dialog" onclick="location.href='{{ url('/group/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="groupUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="groupUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="group_id_chk_all" id="group_id_chk_all" onclick="checkAllCheckboxes(this,'group-id')"> ID</th>
                            <th>Group Name</th>
                            <th>Group Type</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($group_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="group_id_chk" id="group_id_chk" value="{{$group_list[$i]['id']}}" class="group-id-chk"> {{$group_list[$i]['id']}}</td>
                                    <td>{{$group_list[$i]['group_name']}}</td>
                                    <td>{{$group_list[$i]['group_type']}}</td>
                                    <td>{{($group_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('group/edit/'.$group_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Group" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $group_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$group_list->count()}} of {{ $group_list->total() }} Groups.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="group_delete_submit" name="group_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','group');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="group_enable_submit" name="group_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','group');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="group_disable_submit" name="group_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','group');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
