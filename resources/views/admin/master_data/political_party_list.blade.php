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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Political Party" class="btn btn-dialog" onclick="location.href='{{ url('/political-party/add') }}'"></div>
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
                            <th>Political Party Name</th>
                            <th>Short Name</th>    
                            <th>Party Status</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($pp_list);$i++)
                                <tr>  
                                    <td>{{$pp_list[$i]['id']}}</td>
                                    <td>{{$pp_list[$i]['party_name']}}</td>
                                    <td>{{$pp_list[$i]['party_short_name']}}</td>
                                    <td>{{$pp_list[$i]['party_status']}}</td>
                                    <td>{{($pp_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('political-party/edit/'.$pp_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Political Party" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $pp_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$pp_list->count()}} of {{ $pp_list->total() }} Political Party.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
