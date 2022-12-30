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
                                <input type="text" name="name" id="name" placeholder="Package Name" class="form-control" value="{{request('name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Subscriber Package" class="btn btn-dialog" onclick="location.href='{{ url('subscriber-package/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="packageSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="packageErrorMessage"></div>

            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="package_id_chk_all" id="package_id_chk_all" onclick="checkAllCheckboxes(this,'package-id')"> ID</th>
                            <th>Subscriber Name</th>
                            <th>Package Name</th>
                           
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($subscriber_package_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="package_id_chk" id="package_id_chk" value="{{$subscriber_package_list[$i]['id']}}" class="package-id-chk"> {{$subscriber_package_list[$i]['id']}}</td>
                                    <td>{{$subscriber_package_list[$i]['subscriber_name']}}</td>
                                    <td>{{$subscriber_package_list[$i]['package_name']}}</td>
                                    <td>{{($subscriber_package_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('subscriber-package/edit/'.$subscriber_package_list[$i]['id'])}}" class="package-list-edit"><i  title="Edit Package" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $subscriber_package_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$subscriber_package_list->count()}} of {{ $subscriber_package_list->total() }} Subscriber Packages.</p>
                    </p>
                    
                </div>
                
                <div class="form-row ">
                    
                    <div class="form-group col-md-12" >
                        <button type="button" id="package_delete_submit" name="package_delete_submit" class="btn btn-dialog" onclick="updateSubscriberPackages('delete');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                        <button type="button" id="package_enable_submit" name="package_enable_submit" class="btn btn-dialog" onclick="updateSubscriberPackages('enable');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                        <button type="button" id="package_disable_submit" name="package_disable_submit" class="btn btn-dialog" onclick="updateSubscriberPackages('disable');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
                   </div>    
                </div>
                <div class="separator-10">&nbsp;</div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/package.js') }}" ></script>
@endsection
