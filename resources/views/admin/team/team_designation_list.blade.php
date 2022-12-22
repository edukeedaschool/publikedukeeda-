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
                                <input type="text" name="des_name" id="des_name" placeholder="Designation Name" class="form-control" value="{{request('des_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Designation" class="btn btn-dialog" onclick="location.href='{{ url('/team-designation/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="designationSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="designationErrorMessage"></div>

            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="des_id_chk_all" id="des_id_chk_all" onclick="checkAllCheckboxes(this,'des-id')"> ID</th>
                            <th>Designation Name</th>
                            <th>Representation Area</th>    
                            <th>Subscriber</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($designation_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="des_id_chk" id="des_id_chk" value="{{$designation_list[$i]['id']}}" class="des-id-chk"> {{$designation_list[$i]['id']}}</td>
                                    <td>{{$designation_list[$i]['designation_name']}}</td>
                                    <td>{{$designation_list[$i]['representation_area']}}</td>
                                    <td>{{$designation_list[$i]['subscriber_name']}}</td>
                                    <td>{{($designation_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('team-designation/edit/'.$designation_list[$i]['id'])}}" class="designation-list-edit"><i  title="Edit Designation" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $designation_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$designation_list->count()}} of {{ $designation_list->total() }} Designations.</p>
                    </p>
                    
                </div>
                
                <div class="form-row ">
                    
                    <div class="form-group col-md-12" >
                        <button type="button" id="designation_delete_submit" name="designation_delete_submit" class="btn btn-dialog" onclick="updateDesignations('delete');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                        <button type="button" id="designation_enable_submit" name="designation_enable_submit" class="btn btn-dialog" onclick="updateDesignations('enable');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                        <button type="button" id="designation_disable_submit" name="designation_disable_submit" class="btn btn-dialog" onclick="updateDesignations('disable');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
                   </div>    
                </div>
                <div class="separator-10">&nbsp;</div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/team.js') }}" ></script>
@endsection
