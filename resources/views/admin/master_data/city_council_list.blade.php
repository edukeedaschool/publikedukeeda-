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
                                <input type="text" name="cc_name" id="cc_name" placeholder="City Council" class="form-control" value="{{request('cc_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add City Council" class="btn btn-dialog" onclick="location.href='{{ url('/city-council/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="ccUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="ccUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="cc_id_chk_all" id="cc_id_chk_all" onclick="checkAllCheckboxes(this,'cc-id')"> ID</th>
                            <th>City Council Name</th>
                            <th>District</th>    
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($city_council_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="cc_id_chk" id="cc_id_chk" value="{{$city_council_list[$i]['id']}}" class="cc-id-chk"> {{$city_council_list[$i]['id']}}</td>
                                    <td>{{$city_council_list[$i]['city_council_name']}}</td>
                                    <td>{{$city_council_list[$i]['district_name']}}</td>
                                    <td>{{$city_council_list[$i]['state_name']}}</td>
                                    <td>{{($city_council_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('city-council/edit/'.$city_council_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $city_council_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$city_council_list->count()}} of {{ $city_council_list->total() }} City Council.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="cc_delete_submit" name="cc_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','cc');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="cc_enable_submit" name="cc_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','cc');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="cc_disable_submit" name="cc_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','cc');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
