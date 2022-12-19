@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="groupsContainer">
                <form class="" name="editReviewLevelFrm" id="editReviewLevelFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editReviewLevelSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editReviewLevelErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Review Level</label>
                            <input id="reviewLevel" type="text" class="form-control" name="reviewLevel" value="{{$review_level_data->review_level}}" >
                            <div class="invalid-feedback" id="error_validation_reviewLevel"></div>
                            <input id="review_level_id" type="hidden" name="review_level_id" value="{{$review_level_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Designation</label>
                            <input id="designation" type="text" class="form-control" name="designation" value="{{$review_level_data->designation}}" >
                            <div class="invalid-feedback" id="error_validation_designation"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Position</label>
                            <select id="position" class="form-control" name="position" >
                                <option value="">Position</option>
                                @foreach($positions as $key=>$value)
                                    <?php $sel = ($key == $review_level_data->position)?'selected':''; ?>
                                    <option {{$sel}} value="{{$key}}">{{$value}}</option>
                                @endforeach  
                            </select>    
                            <div class="invalid-feedback" id="error_validation_position"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="status" class="form-control" name="status">
                                <option value="">Status</option>
                                <option value="1" @if($review_level_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($review_level_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_submissionTypeStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <button type="button" id="ReviewLevel_edit_cancel" name="ReviewLevel_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('submission-type/list')}}'">Cancel</button>
                            <button type="button" id ="ReviewLevel_edit_submit" name="ReviewLevel_edit_submit" class="btn btn-dialog" onclick="submitEditReviewLevel();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
@endsection
