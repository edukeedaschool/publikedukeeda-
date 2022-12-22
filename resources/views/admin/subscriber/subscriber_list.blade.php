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
                                <input type="text" name="sub_name" id="sub_name" placeholder="Subscriber Name" class="form-control" value="{{request('sub_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Subscriber" class="btn btn-dialog" onclick="location.href='{{ url('/subscriber/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            
            <div class="alert alert-success alert-dismissible elem-hidden" id="subscriberSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="subscriberErrorMessage"></div>

            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="sub_id_chk_all" id="sub_id_chk_all" onclick="checkAllCheckboxes(this,'sub-id')"> ID</th>
                            <th>Subscriber Name</th>
                            <th>Office Belongs To</th>    
                            <th>Country</th>    
                            <th>State</th>    
                            <th>District</th>   
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($subscriber_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="sub_id_chk" id="sub_id_chk" value="{{$subscriber_list[$i]['id']}}" class="sub-id-chk"> {{$subscriber_list[$i]['id']}}</td>
                                    <td>{{$subscriber_list[$i]['subscriber_name']}}</td>
                                    <td>{{$subscriber_list[$i]['sub_group_name']}}</td>
                                    <td>{{$subscriber_list[$i]['country_name']}}</td>
                                    <td>{{$subscriber_list[$i]['state_name']}}</td>
                                    <td>{{$subscriber_list[$i]['district_name']}}</td>
                                    <td>{{($subscriber_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('subscriber/edit/'.$subscriber_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Subscriber" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $subscriber_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$subscriber_list->count()}} of {{ $subscriber_list->total() }} Subscribers.</p>
                    </p>
                    
                </div>
                
                <div class="form-row ">
                    <div class="separator-10">&nbsp;</div>
                    <div class="form-group col-md-12" >
                        <button type="button" id="subscriber_delete_submit" name="subscriber_delete_submit" class="btn btn-dialog" onclick="updateSubscribers('delete');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                        <button type="button" id="subscriber_enable_submit" name="subscriber_enable_submit" class="btn btn-dialog" onclick="updateSubscribers('enable');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                        <button type="button" id="subscriber_disable_submit" name="subscriber_disable_submit" class="btn btn-dialog" onclick="updateSubscribers('disable');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
                   </div>    
                </div>
                
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/subscriber.js') }}" ></script>
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
