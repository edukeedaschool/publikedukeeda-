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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Department" class="btn btn-dialog" onclick="location.href='{{ url('/govt-department/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            
           <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="government_DepartmentUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="government_DepartmentUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="government_Department_id_chk_all" id="government_Department_id_chk_all" onclick="checkAllCheckboxes(this,'government_Department-id')"> ID</th>
                            <th>Department Name</th>
                            <th>Country</th>
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($govt_dept_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="government_Department_id_chk" id="government_Department_id_chk" value="{{$govt_dept_list[$i]['id']}}" class="government_Department-id-chk"> {{$govt_dept_list[$i]['id']}}</td>
                                    <td>{{$govt_dept_list[$i]['department_name']}}</td>
                                    <td>{{$govt_dept_list[$i]['country_name']}}</td>
                                    <td>{{$govt_dept_list[$i]['state_name']}}</td>
                                    <td>{{($govt_dept_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('govt-department/edit/'.$govt_dept_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Government Department" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $govt_dept_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$govt_dept_list->count()}} of {{ $govt_dept_list->total() }} Government Department.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="government_Department_delete_submit" name="government_Department_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','government_Department');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="government_Department_enable_submit" name="government_Department_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','government_Department');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="government_Department_disable_submit" name="government_Department_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','government_Department');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
