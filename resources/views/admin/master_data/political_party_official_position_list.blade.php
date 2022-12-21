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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Position" class="btn btn-dialog" onclick="location.href='{{ url('/political-party-official-position/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            
           <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="political_Party_Official_PositionUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="political_Party_Official_PositionUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="political_Party_Official_Position_id_chk_all" id="political_Party_Official_Position_id_chk_all" onclick="checkAllCheckboxes(this,'political_Party_Official_Position-id')"> ID</th>
                            <th>Position Name</th>
                            <th>Representation Area</th>
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($position_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="political_Party_Official_Position_id_chk" id="political_Party_Official_Position_id_chk" value="{{$position_list[$i]['id']}}" class="political_Party_Official_Position-id-chk"> {{$position_list[$i]['id']}}</td>
                                    <td>{{$position_list[$i]['position_name']}}</td>
                                    <td>{{isset($rep_area[$position_list[$i]['representation_area']])?$rep_area[$position_list[$i]['representation_area']]:'' }}</td>
                                    <td>{{($position_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('political-party-official-position/edit/'.$position_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Political Party Official Position" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $position_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$position_list->count()}} of {{ $position_list->total() }} Political Party Official Position.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="political_Party_Official_Position_delete_submit" name="political_Party_Official_Position_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','political_Party_Official_Position');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="political_Party_Official_Position_enable_submit" name="political_Party_Official_Position_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','political_Party_Official_Position');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="political_Party_Official_Position_disable_submit" name="political_Party_Official_Position_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','political_Party_Official_Position');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
