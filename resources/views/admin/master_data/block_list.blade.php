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
                                <input type="text" name="block_name" id="block_name" placeholder="Block Name" class="form-control" value="{{request('block_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Block" class="btn btn-dialog" onclick="location.href='{{ url('/block/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
           
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="blockUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="blockUpdateErrorMessage"></div>

            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="block_id_chk_all" id="block_id_chk_all" onclick="checkAllCheckboxes(this,'block-id')"> ID</th>
                            <th>Block Name</th>
                            <th>District</th>    
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($block_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="block_id_chk" id="block_id_chk" value="{{$block_list[$i]['id']}}" class="block-id-chk"> {{$block_list[$i]['id']}}</td>
                                    <td>{{$block_list[$i]['block_name']}}</td>
                                    <td>{{$block_list[$i]['district_name']}}</td>
                                    <td>{{$block_list[$i]['state_name']}}</td>
                                    <td>{{($block_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('block/edit/'.$block_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Block" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $block_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$block_list->count()}} of {{ $block_list->total() }} Blocks.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="block_delete_submit" name="block_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','block');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="block_enable_submit" name="block_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','block');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="block_disable_submit" name="block_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','block');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
