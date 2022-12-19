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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Postal Code" class="btn btn-dialog" onclick="location.href='{{ url('/postal-code/add') }}'"></div>
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
                            <th>Postal Code</th>
                            <th>Post Office</th>
                            <th>Sub District</th>    
                            <th>District</th>    
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($postal_code_list);$i++)
                                <tr>  
                                    <td>{{$postal_code_list[$i]['id']}}</td>
                                    <td>{{$postal_code_list[$i]['postal_code']}}</td>
                                    <td>{{$postal_code_list[$i]['post_office']}}</td>
                                    <td>{{$postal_code_list[$i]['sub_district_name']}}</td>
                                    <td>{{$postal_code_list[$i]['district_name']}}</td>
                                    <td>{{$postal_code_list[$i]['state_name']}}</td>
                                    <td>{{($postal_code_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('postal-code/edit/'.$postal_code_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Postal Code" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $postal_code_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$postal_code_list->count()}} of {{ $postal_code_list->total() }} Postal Codes.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
