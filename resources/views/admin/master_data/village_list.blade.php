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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Village" class="btn btn-dialog" onclick="location.href='{{ url('/village/add') }}'"></div>
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
                            <th>Village Name</th>
                            <th>Sub District</th>    
                            <th>District</th>    
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($village_list);$i++)
                                <tr>  
                                    <td>{{$village_list[$i]['id']}}</td>
                                    <td>{{$village_list[$i]['village_name']}}</td>
                                    <td>{{$village_list[$i]['sub_district_name']}}</td>
                                    <td>{{$village_list[$i]['district_name']}}</td>
                                    <td>{{$village_list[$i]['state_name']}}</td>
                                    <td>{{($village_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('village/edit/'.$village_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Village" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $village_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$village_list->count()}} of {{ $village_list->total() }} Villages.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
