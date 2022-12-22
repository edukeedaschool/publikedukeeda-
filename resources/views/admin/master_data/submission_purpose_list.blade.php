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
                                <input type="text" name="sub_purpose_name" id="sub_purpose_name" placeholder="Submission Purpose" class="form-control" value="{{request('sub_purpose_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Sub Purpose" class="btn btn-dialog" onclick="location.href='{{ url('/submission-purpose/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="submission_PurposeUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="submission_PurposeUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="submission_Purpose_id_chk_all" id="submission_Purpose_id_chk_all" onclick="checkAllCheckboxes(this,'submission_Purpose-id')"> ID</th>
                            <th>Submission Purpose</th>
                            <th>Group</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($sub_purpose_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="submission_Purpose_id_chk" id="submission_Purpose_id_chk" value="{{$sub_purpose_list[$i]['id']}}" class="submission_Purpose-id-chk"> {{$sub_purpose_list[$i]['id']}}</td>
                                    <td>{{$sub_purpose_list[$i]['submission_purpose']}}</td>
                                    <td>{{$sub_purpose_list[$i]['group_name']}}</td>
                                    <td>{{($sub_purpose_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('submission-purpose/edit/'.$sub_purpose_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Submission Purpose" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $sub_purpose_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$sub_purpose_list->count()}} of {{ $sub_purpose_list->total() }} Submission Purpose.</p>
                    </p>
                    
                </div>
            </div>
           
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="submission_Purpose_delete_submit" name="submission_Purpose_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','submission_Purpose');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="submission_Purpose_enable_submit" name="submission_Purpose_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','submission_Purpose');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="submission_Purpose_disable_submit" name="submission_Purpose_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','submission_Purpose');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
