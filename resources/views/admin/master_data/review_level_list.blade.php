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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Review Level" class="btn btn-dialog" onclick="location.href='{{ url('/review-level/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            
           <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="review_LevelUpdateSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="review_LevelUpdateErrorMessage"></div>
            
            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="review_Level_id_chk_all" id="review_Level_id_chk_all" onclick="checkAllCheckboxes(this,'review_Level-id')"> ID</th>
                            <th>Review Level</th>
                            <th>Designation</th>    
                            <th>Position</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($review_level_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="review_Level_id_chk" id="review_Level_id_chk" value="{{$review_level_list[$i]['id']}}" class="review_Level-id-chk"> {{$review_level_list[$i]['id']}}</td>
                                    <td>{{$review_level_list[$i]['review_level']}}</td>
                                    <td>{{$review_level_list[$i]['designation']}}</td>
                                    <td>{{$review_level_list[$i]['position']}}</td>
                                    <td>{{($review_level_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('review-level/edit/'.$review_level_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Review Level" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $review_level_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$review_level_list->count()}} of {{ $review_level_list->total() }} Review Level.</p>
                    </p>
                    
                </div>
            </div>
            
            <div class="form-row ">
                <div class="separator-10">&nbsp;</div>
                <div class="form-group col-md-12" >
                    <button type="button" id="review_Level_delete_submit" name="review_Level_delete_submit" class="btn btn-dialog" onclick="updateBulkData('delete','review_Level');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                    <button type="button" id="review_Level_enable_submit" name="review_Level_enable_submit" class="btn btn-dialog" onclick="updateBulkData('enable','review_Level');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                    <button type="button" id="review_Level_disable_submit" name="review_Level_disable_submit" class="btn btn-dialog" onclick="updateBulkData('disable','review_Level');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
               </div>    
            </div>
            
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
