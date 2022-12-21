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
                                <input type="text" name="mc1_name" id="mc1_name" placeholder="Municipal corporation" class="form-control" value="{{request('mc1_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addStateBtn" id="addStateBtn" value="Add MC" class="btn btn-dialog" onclick="location.href='{{ url('/mc1/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="mc1UpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="mc1UpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="mc1_id_chk_all" id="mc1_id_chk_all" onclick="checkAllCheckboxes(this,'mc1-id')"> ID</th>
                            <th>MC Name</th>
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($mc1_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="mc1_id_chk" id="mc1_id_chk" value="{{$mc1_list[$i]['id']}}" class="mc1-id-chk"> {{$mc1_list[$i]['id']}}</td>
                                    <td>{{$mc1_list[$i]['mc_name']}}</td>
                                    <td>{{$mc1_list[$i]['state_name']}}</td>
                                    <td>{{($mc1_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('mc1/edit/'.$mc1_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                    {{ $mc1_list->withQueryString()->links('pagination::bootstrap-4') }}
                    <p>Displaying {{$mc1_list->count()}} of {{ $mc1_list->total() }} MC.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="mc1_delete_submit" name="mc1_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','mc1');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="mc1_enable_submit" name="mc1_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','mc1');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="mc1_disable_submit" name="mc1_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','mc1');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
