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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Department" class="btn btn-dialog" onclick="location.href='{{ url('/govt-department/add') }}'"></div>
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
                            <th>Department Name</th>
                            <th>Country</th>
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($govt_dept_list);$i++)
                                <tr>  
                                    <td>{{$govt_dept_list[$i]['id']}}</td>
                                    <td>{{$govt_dept_list[$i]['department_name']}}</td>
                                    <td>{{$govt_dept_list[$i]['country_name']}}</td>
                                    <td>{{$govt_dept_list[$i]['state_name']}}</td>
                                    <td>{{($govt_dept_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('govt-department/edit/'.$govt_dept_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Government Department" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $govt_dept_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$govt_dept_list->count()}} of {{ $govt_dept_list->total() }} Government Department.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
