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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add City Council" class="btn btn-dialog" onclick="location.href='{{ url('/city-council/add') }}'"></div>
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
                            <th>City Council Name</th>
                            <th>District</th>    
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($city_council_list);$i++)
                                <tr>  
                                    <td>{{$city_council_list[$i]['id']}}</td>
                                    <td>{{$city_council_list[$i]['city_council_name']}}</td>
                                    <td>{{$city_council_list[$i]['district_name']}}</td>
                                    <td>{{$city_council_list[$i]['state_name']}}</td>
                                    <td>{{($city_council_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('city-council/edit/'.$city_council_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $city_council_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$city_council_list->count()}} of {{ $city_council_list->total() }} City Council.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
