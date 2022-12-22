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
                                <input type="text" name="member_name" id="member_name" placeholder="Team Member" class="form-control" value="{{request('member_name')}}">
                            </div>
                            <div class="col-md-2" >
                                <input type="submit" name="searchBtn" id="searchBtn" value="Search" class="btn btn-dialog" >
                            </div>
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Team Member" class="btn btn-dialog" onclick="location.href='{{ url('/team-member/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>
            <div class="alert alert-success alert-dismissible elem-hidden" id="memberSuccessMessage"></div>
            <div class="alert alert-danger alert-dismissible elem-hidden"  id="memberErrorMessage"></div>

            <div id="requestsContainer">
                <div class="table-responsive">
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th><input type="checkbox" name="member_id_chk_all" id="member_id_chk_all" onclick="checkAllCheckboxes(this,'member-id')"> ID</th>
                            <th>Member Name</th>
                            <th>Designation Name</th>    
                            <th>Subscriber</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($member_list);$i++)
                                <tr>  
                                    <td><input type="checkbox" name="member_id_chk" id="member_id_chk" value="{{$member_list[$i]['id']}}" class="member-id-chk"> {{$member_list[$i]['id']}}</td>
                                    <td>{{$member_list[$i]['member_name']}}</td>
                                    <td>{{$member_list[$i]['designation_name']}}</td>
                                    <td>{{$member_list[$i]['subscriber_name']}}</td>
                                    <td>{{($member_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('team-member/edit/'.$member_list[$i]['id'])}}" class="member-list-edit"><i  title="Edit Designation" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $member_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$member_list->count()}} of {{ $member_list->total() }} Team Members.</p>
                    </p>
                    
                </div>
                
                <div class="form-row ">
                    <div class="form-group col-md-12" >
                        <button type="button" id="member_delete_submit" name="member_delete_submit" class="btn btn-dialog" onclick="updateTeamMember('delete');"><i title="Delete Selected" class="fa fa-trash fas-icon" ></i> Delete Selected</button>
                        <button type="button" id="member_enable_submit" name="member_enable_submit" class="btn btn-dialog" onclick="updateTeamMember('enable');"><i title="Enable Selected" class="fa fa-check-circle fas-icon" ></i> Enable Selected</button>
                        <button type="button" id="member_disable_submit" name="member_disable_submit" class="btn btn-dialog" onclick="updateTeamMember('disable');"><i title="Disable Selected" class="fa fa-ban fas-icon" ></i> Disable Selected</button>
                    </div>    
                </div>
                <div class="separator-10">&nbsp;</div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/team.js') }}" ></script>
@endsection
