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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Position" class="btn btn-dialog" onclick="location.href='{{ url('/political-party-official-position/add') }}'"></div>
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
                            <th>Position Name</th>
                            <th>Representation Area</th>
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($position_list);$i++)
                                <tr>  
                                    <td>{{$position_list[$i]['id']}}</td>
                                    <td>{{$position_list[$i]['position_name']}}</td>
                                    <td>{{isset($rep_area[$position_list[$i]['representation_area']])?$rep_area[$position_list[$i]['representation_area']]:'' }}</td>
                                    <td>{{($position_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('political-party-official-position/edit/'.$position_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Political Party Official Position" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $position_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$position_list->count()}} of {{ $position_list->total() }} Political Party Official Position.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
