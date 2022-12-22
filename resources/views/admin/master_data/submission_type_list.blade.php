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
                            <div class="col-md-3" >
                                <input type="text" name="sub_type" id="sub_type" placeholder="Submission Type" class="form-control" value="{{request('sub_type')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Sub Type" class="btn btn-dialog" onclick="location.href='{{ url('/submission-type/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            
           <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="submission_TypeUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="submission_TypeUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="submission_Type_id_chk_all" id="submission_Type_id_chk_all" onclick="checkAllCheckboxes(this,'submission_Type-id')"> ID</th>
                            <th>Submission Type</th>
                            <th>Group</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($sub_type_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="submission_Type_id_chk" id="submission_Type_id_chk" value="{{$sub_type_list[$i]['id']}}" class="submission_Type-id-chk"> {{$sub_type_list[$i]['id']}}</td>
                                    <td>{{$sub_type_list[$i]['submission_type']}}</td>
                                    <td>{{$sub_type_list[$i]['group_name']}}</td>
                                    <td>{{($sub_type_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('submission-type/edit/'.$sub_type_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Submission Type" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $sub_type_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$sub_type_list->count()}} of {{ $sub_type_list->total() }} Submission Type.</p>
                    </p>
                    
                </div>
            </div>
           
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="submission_Type_delete_submit" name="submission_Type_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','submission_Type');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="submission_Type_enable_submit" name="submission_Type_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','submission_Type');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="submission_Type_disable_submit" name="submission_Type_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','submission_Type');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
