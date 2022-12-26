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
                                <input type="text" name="ro_name" id="ro_name" placeholder="Review Official" class="form-control" value="{{request('ro_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Review Official" class="btn btn-dialog" onclick="location.href='{{ url('/review-official/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="roSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="roErrorMessage"></div>

            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="ro_id_chk_all" id="ro_id_chk_all" onclick="checkAllCheckboxes(this,'ro-id')"> ID</th>
                            <th>Member Name</th>
                            <th>Designation Name</th>    
                            <th>Subscriber</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($ro_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="ro_id_chk" id="ro_id_chk" value="{{$ro_list[$i]['id']}}" class="ro-id-chk"> {{$ro_list[$i]['id']}}</td>
                                    <td>{{$ro_list[$i]['ro_name']}}</td>
                                    <td>{{$ro_list[$i]['designation']}}</td>
                                    <td>{{$ro_list[$i]['subscriber_name']}}</td>
                                    <td>{{($ro_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('review-official/edit/'.$ro_list[$i]['id'])}}" class="ro-list-edit"><i  title="Edit Designation" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $ro_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$ro_list->count()}} of {{ $ro_list->total() }} Review Officials.</p>
                    </p>
                    
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-12" >
                        <button type="button" id="ro_delete_submit" name="ro_delete_submit" class="btn btn-dialog" onclick="updateReviewOfficial('delete');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                        <button type="button" id="ro_enable_submit" name="ro_enable_submit" class="btn btn-dialog" onclick="updateReviewOfficial('enable');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                        <button type="button" id="ro_disable_submit" name="ro_disable_submit" class="btn btn-dialog" onclick="updateReviewOfficial('disable');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
                    </div>    
                </div>
                <div class="separator-10">&nbsp;</div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/subscriber.js') }}" ></script>
@endsection
