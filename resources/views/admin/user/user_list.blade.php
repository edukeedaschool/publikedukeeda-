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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add User" class="btn btn-dialog" onclick="location.href='{{ url('/user/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="userSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="userErrorMessage"></div>

            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="user_id_chk_all" id="user_id_chk_all" onclick="checkAllCheckboxes(this,'user-id')"> ID</th>
                            <th>Name</th>
                            <th>Email</th>    
                            <th>Type</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($user_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="user_id_chk" id="user_id_chk" value="{{$user_list[$i]['id']}}" class="user-id-chk"> {{$user_list[$i]['id']}}</td>
                                    <td>{{$user_list[$i]['name']}}</td>
                                    <td>{{$user_list[$i]['email']}}</td>
                                    <td>{{$user_list[$i]['role_name']}}</td>
                                    <td>{{($user_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('user/edit/'.$user_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit User" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $user_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$user_list->count()}} of {{ $user_list->total() }} Users.</p>
                    </p>
                    
                </div>
                
                <div class="form-row ">
                    
                    <div class="form-group col-md-12" >
                        <button type="button" id="user_delete_submit" name="user_delete_submit" class="btn btn-dialog" onclick="updateUsers('delete');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                        <button type="button" id="user_enable_submit" name="user_enable_submit" class="btn btn-dialog" onclick="updateUsers('enable');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                        <button type="button" id="user_disable_submit" name="user_disable_submit" class="btn btn-dialog" onclick="updateUsers('disable');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i>  Disable Selected</button>
                   </div>    
                </div>
                <div class="separator-10">&nbsp;</div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/users.js') }}" ></script>
@endsection
