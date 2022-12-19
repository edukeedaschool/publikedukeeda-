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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Sub Group" class="btn btn-dialog" onclick="location.href='{{ url('/sub-group/add') }}'"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="separator-10">&nbsp;</div>

            <div id="requestsContainer">
                <div class="table-responsive">
                         
                    <table class="table table-striped clearfix admin-table" cellspacing="0">
                        <thead><tr class="header-tr">
                            <th>ID</th>
                            <th>Sub Group Name</th>
                            <th>Group</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($sub_group_list);$i++)
                                <tr>  
                                    <td>{{$sub_group_list[$i]['id']}}</td>
                                    <td>{{$sub_group_list[$i]['sub_group_name']}}</td>
                                    <td>{{$sub_group_list[$i]['group_name']}}</td>
                                    <td>{{($sub_group_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('sub-group/edit/'.$sub_group_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Sub Group" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $sub_group_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$sub_group_list->count()}} of {{ $sub_group_list->total() }} Sub Groups.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
@endsection
