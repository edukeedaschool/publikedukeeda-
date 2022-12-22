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
                                <input type="text" name="org_name" id="org_name" placeholder="Organization Name" class="form-control" value="{{request('org_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Organization" class="btn btn-dialog" onclick="location.href='{{ url('/non-profit-organization/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="non_Profit_OrganizationUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="non_Profit_OrganizationUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="non_Profit_Organization_id_chk_all" id="non_Profit_Organization_id_chk_all" onclick="checkAllCheckboxes(this,'non_Profit_Organization-id')"> ID</th>
                            <th>Organization Name</th>
                            <th>Short Name</th>
                            <th>Type</th>
                            <th>Working Area</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($np_org_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="non_Profit_Organization_id_chk" id="non_Profit_Organization_id_chk" value="{{$np_org_list[$i]['id']}}" class="non_Profit_Organization-id-chk"> {{$np_org_list[$i]['id']}}</td>
                                    <td>{{$np_org_list[$i]['organization_name']}}</td>
                                    <td>{{$np_org_list[$i]['organization_short_name']}}</td>
                                    <td>{{$np_org_list[$i]['organization_type']}}</td>
                                    <td>{{$np_org_list[$i]['working_area']}}</td>
                                    <td>{{($np_org_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('non-profit-organization/edit/'.$np_org_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Non Profit Organization" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $np_org_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$np_org_list->count()}} of {{ $np_org_list->total() }} Non Profit Organization.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="non_Profit_Organization_delete_submit" name="non_Profit_Organization_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','non_Profit_Organization');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="non_Profit_Organization_enable_submit" name="non_Profit_Organization_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','non_Profit_Organization');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="non_Profit_Organization_disable_submit" name="non_Profit_Organization_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','non_Profit_Organization');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
