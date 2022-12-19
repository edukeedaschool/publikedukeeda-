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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Organization" class="btn btn-dialog" onclick="location.href='{{ url('/non-profit-organization/add') }}'"></div>
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
                            <th>Organization Name</th>
                            <th>Short Name</th>
                            <th>Type</th>
                            <th>Working Area</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($np_org_list);$i++)
                                <tr>  
                                    <td>{{$np_org_list[$i]['id']}}</td>
                                    <td>{{$np_org_list[$i]['organization_name']}}</td>
                                    <td>{{$np_org_list[$i]['organization_short_name']}}</td>
                                    <td>{{$np_org_list[$i]['organization_type']}}</td>
                                    <td>{{$np_org_list[$i]['working_area']}}</td>
                                    <td>{{($np_org_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('non-profit-organization/edit/'.$np_org_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Non Profit Organization" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $np_org_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$np_org_list->count()}} of {{ $np_org_list->total() }} Non Profit Organization.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
