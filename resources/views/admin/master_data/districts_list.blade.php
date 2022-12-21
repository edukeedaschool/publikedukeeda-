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
                                <input type="text" name="dis_name" id="dis_name" placeholder="District" class="form-control" value="{{request('dis_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addStateBtn" id="addStateBtn" value="Add District" class="btn btn-dialog" onclick="location.href='{{ url('/district/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            
            <div class="alert alert-success alert-dismissible elem-hidden" id="districtUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="districtUpdateErrorMessage"></div>

            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="dis_id_chk_all" id="dis_id_chk_all" onclick="checkAllCheckboxes(this,'district-id')"> ID</th>
                            <th>District</th>
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($districts_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="dis_id_chk" id="dis_id_chk" value="{{$districts_list[$i]['id']}}" class="district-id-chk"> {{$districts_list[$i]['id']}}</td>
                                    <td>{{$districts_list[$i]['district_name']}}</td>
                                    <td>{{$districts_list[$i]['state_name']}}</td>
                                    <td>{{($districts_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('district/edit/'.$districts_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit District" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                    {{ $districts_list->withQueryString()->links('pagination::bootstrap-4') }}
                    <p>Displaying {{$districts_list->count()}} of {{ $districts_list->total() }} Districts.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="district_delete_submit" name="district_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','district');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="district_enable_submit" name="district_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','district');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="district_disable_submit" name="district_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','district');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>

@endsection
