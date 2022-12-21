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
                                <input type="text" name="mc2_name" id="mc2_name" placeholder="Municipal Corporation" class="form-control" value="{{request('mc2_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add MC" class="btn btn-dialog" onclick="location.href='{{ url('/mc2/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            
            <div class="alert alert-success alert-dismissible elem-hidden" id="mc2UpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="mc2UpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="mc2_id_chk_all" id="mc2_id_chk_all" onclick="checkAllCheckboxes(this,'mc2-id')"> ID</th>
                            <th>MC Name</th>
                            <th>District</th>    
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($mc2_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="mc2_id_chk" id="mc2_id_chk" value="{{$mc2_list[$i]['id']}}" class="mc2-id-chk"> {{$mc2_list[$i]['id']}}</td>
                                    <td>{{$mc2_list[$i]['mc_name']}}</td>
                                    <td>{{$mc2_list[$i]['district_name']}}</td>
                                    <td>{{$mc2_list[$i]['state_name']}}</td>
                                    <td>{{($mc2_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('mc2/edit/'.$mc2_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                    {{ $mc2_list->withQueryString()->links('pagination::bootstrap-4') }}
                    <p>Displaying {{$mc2_list->count()}} of {{ $mc2_list->total() }} MC.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="mc2_delete_submit" name="mc2_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','mc2');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="mc2_enable_submit" name="mc2_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','mc2');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="mc2_disable_submit" name="mc2_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','mc2');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
