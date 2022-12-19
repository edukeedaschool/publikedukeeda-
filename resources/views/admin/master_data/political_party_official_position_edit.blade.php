@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="ccsContainer">
                <form class="" name="editPositionFrm" id="editPositionFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editPositionSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editPositionErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Position Name</label>
                            <input id="positionName" type="text" class="form-control" name="positionName" value="{{$position_data->position_name}}" >
                            <div class="invalid-feedback" id="error_validation_positionName"></div>
                            <input id="position_id" type="hidden" name="position_id" value="{{$position_data->id}}" >
                        </div>
                    </div>    
                    
                   <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Representation Area</label>
                            <select id="positionRepresentationArea" class="form-control" name="positionRepresentationArea" >
                                <option value="">Representation Area</option>
                                @foreach($rep_area as $key=>$value)
                                    <?php $sel = ($key == $position_data->representation_area)?'selected':''; ?>
                                    <option {{$sel}} value="{{$key}}">{{$value}}</option>
                                @endforeach    
                            </select> 
                            <div class="invalid-feedback" id="error_validation_positionRepresentationArea"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="positionStatus" class="form-control" name="positionStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($position_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($position_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_positionStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="position_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="position_edit_cancel" name="position_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('political-party-official-position/list')}}'">Cancel</button>
                           <button type="button" id ="position_edit_submit" name="position_edit_submit" class="btn btn-dialog" onclick="submitEditPoliticalPartyOfficialPosition();">Submit</button>
                       </div>    
                    </div>  
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="{{ asset('js/master_data.js') }}" ></script>
<script type="text/javascript">
$(document).ready(function(){
});
</script>
@endsection
