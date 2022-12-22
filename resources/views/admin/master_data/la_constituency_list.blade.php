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
                                <input type="text" name="lac_name" id="lac_name" placeholder="Constituency Name" class="form-control" value="{{request('lac_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Constituency" class="btn btn-dialog" onclick="location.href='{{ url('/la-constituency/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="LACUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="LACUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="LAC_id_chk_all" id="LAC_id_chk_all" onclick="checkAllCheckboxes(this,'LAC-id')"> ID</th>
                            <th>Constituency Name</th>
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($la_constituency_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="LAC_id_chk" id="LAC_id_chk" value="{{$la_constituency_list[$i]['id']}}" class="LAC-id-chk"> {{$la_constituency_list[$i]['id']}}</td>
                                    <td>{{$la_constituency_list[$i]['constituency_name']}}</td>
                                    <td>{{$la_constituency_list[$i]['state_name']}}</td>
                                    <td>{{($la_constituency_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('la-constituency/edit/'.$la_constituency_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Constituency" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $la_constituency_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$la_constituency_list->count()}} of {{ $la_constituency_list->total() }} Legislative Assembly Constituency.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="LAC_delete_submit" name="LAC_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','LAC');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="LAC_enable_submit" name="LAC_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','LAC');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="LAC_disable_submit" name="LAC_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','LAC');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
