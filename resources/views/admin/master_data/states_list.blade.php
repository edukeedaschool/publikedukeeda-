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
                            <div class="col-md-3" ><input type="button" name="addStateBtn" id="addStateBtn" value="Add State" class="btn btn-dialog" onclick="location.href='{{ url('/state/add') }}'"></div>
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
                            <th>State</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($states_list);$i++)
                            <tr>  
                                <td>{{$states_list[$i]['id']}}</td>
                                <td>{{$states_list[$i]['state_name']}}</td>
                                <td>{{($states_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                <td>
                                    <a href="{{url('state/edit/'.$states_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit State" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                    {{ $states_list->withQueryString()->links('pagination::bootstrap-4') }}
                    <p>Displaying {{$states_list->count()}} of {{ $states_list->total() }} States.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
