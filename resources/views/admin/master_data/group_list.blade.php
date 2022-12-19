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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Group" class="btn btn-dialog" onclick="location.href='{{ url('/group/add') }}'"></div>
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
                            <th>Group Name</th>
                            <th>Group Type</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($group_list);$i++)
                                <tr>  
                                    <td>{{$group_list[$i]['id']}}</td>
                                    <td>{{$group_list[$i]['group_name']}}</td>
                                    <td>{{$group_list[$i]['group_type']}}</td>
                                    <td>{{($group_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('group/edit/'.$group_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Group" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $group_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$group_list->count()}} of {{ $group_list->total() }} Groups.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
