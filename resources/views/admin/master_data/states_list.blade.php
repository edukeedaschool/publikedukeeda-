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
                            <div class="col-md-3" ><input type="button" name="addStateBtn" id="addStateBtn" value="Add State" class="btn btn-dialog" onclick="location.href='{{ url('/state/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="stateUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="stateUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="state_id_chk_all" id="state_id_chk_all" onclick="checkAllCheckboxes(this,'state-id')"> ID</th>
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($states_list);$i++)
                            <tr>  
                                <td><input type="checkbox" name="state_id_chk" id="state_id_chk" value="{{$states_list[$i]['id']}}" class="state-id-chk"> {{$states_list[$i]['id']}}</td>
                                <td>{{$states_list[$i]['state_name']}}</td>
                                <td>{{($states_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                <td>
                                    <a href="{{url('state/edit/'.$states_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit State" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                    {{ $states_list->withQueryString()->links('pagination::bootstrap-4') }}
                    <p>Displaying {{$states_list->count()}} of {{ $states_list->total() }} States.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="state_delete_submit" name="state_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','state');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="state_enable_submit" name="state_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','state');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="state_disable_submit" name="state_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','state');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
