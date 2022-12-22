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
                                <input type="text" name="village_name" id="village_name" placeholder="Village Name" class="form-control" value="{{request('village_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Village" class="btn btn-dialog" onclick="location.href='{{ url('/village/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            
            <div class="alert alert-success alert-dismissible elem-hidden" id="villageUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="villageUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="village_id_chk_all" id="village_id_chk_all" onclick="checkAllCheckboxes(this,'village-id')"> ID</th>
                            <th>Village Name</th>
                            <th>Sub District</th>    
                            <th>District</th>    
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($village_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="village_id_chk" id="village_id_chk" value="{{$village_list[$i]['id']}}" class="village-id-chk"> {{$village_list[$i]['id']}}</td>
                                    <td>{{$village_list[$i]['village_name']}}</td>
                                    <td>{{$village_list[$i]['sub_district_name']}}</td>
                                    <td>{{$village_list[$i]['district_name']}}</td>
                                    <td>{{$village_list[$i]['state_name']}}</td>
                                    <td>{{($village_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('village/edit/'.$village_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Village" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $village_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$village_list->count()}} of {{ $village_list->total() }} Villages.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="village_delete_submit" name="village_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','village');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="village_enable_submit" name="village_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','village');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="village_disable_submit" name="village_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','village');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
