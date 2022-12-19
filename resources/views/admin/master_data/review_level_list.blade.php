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
                            <div class="col-md-3" ><input type="button" name="addBtn" id="addBtn" value="Add Review Level" class="btn btn-dialog" onclick="location.href='{{ url('/review-level/add') }}'"></div>
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
                            <th>Review Level</th>
                            <th>Designation</th>    
                            <th>Position</th>    
                            <th>Status</th>
                            <th>Action</th></tr>
                        </thead>
                        <tbody>
                            @for($i=0;$i<count($review_level_list);$i++)
                                <tr>  
                                    <td>{{$review_level_list[$i]['id']}}</td>
                                    <td>{{$review_level_list[$i]['review_level']}}</td>
                                    <td>{{$review_level_list[$i]['designation']}}</td>
                                    <td>{{$review_level_list[$i]['position']}}</td>
                                    <td>{{($review_level_list[$i]['status'] == 1)?'Enabled':'Disabled'}}</td>
                                    <td>
                                        <a href="{{url('review-level/edit/'.$review_level_list[$i]['id'])}}" class="user-list-edit"><i  title="Edit Review Level" class="far fa-edit"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    
                    <p class="pagination">
                        {{ $review_level_list->withQueryString()->links('pagination::bootstrap-4') }}
                        <p>Displaying {{$review_level_list->count()}} of {{ $review_level_list->total() }} Review Level.</p>
                    </p>
                    
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
@endsection
