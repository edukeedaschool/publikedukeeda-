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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Ward" class="btn btn-dialog" onclick="location.href='{{ url('/ward/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="wardUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="wardUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="ward_id_chk_all" id="ward_id_chk_all" onclick="checkAllCheckboxes(this,'ward-id')"> ID</th>
                            <th>Ward Name</th>
                            <th>District</th>    
                            <th>Municipal Corporation</th>
                            <th>Municipality</th>
                            <th>City Council</th>
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($ward_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="ward_id_chk" id="ward_id_chk" value="{{$ward_list[$i]['id']}}" class="ward-id-chk"> {{$ward_list[$i]['id']}}</td>
                                    <td>{{$ward_list[$i]['ward_name']}}</td>
                                    <td>{{$ward_list[$i]['district_name']}}</td>
                                    <td>{{$ward_list[$i]['mc1_name']}}</td>
                                    <td>{{$ward_list[$i]['mc2_name']}}</td>
                                    <td>{{$ward_list[$i]['city_council_name']}}</td>
                                    <td>{{$ward_list[$i]['state_name']}}</td>
                                    <td>{{($ward_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('ward/edit/'.$ward_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Ward" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $ward_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$ward_list->count()}} of {{ $ward_list->total() }} Wards.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="ward_delete_submit" name="ward_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','ward');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="ward_enable_submit" name="ward_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','ward');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="ward_disable_submit" name="ward_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','ward');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
