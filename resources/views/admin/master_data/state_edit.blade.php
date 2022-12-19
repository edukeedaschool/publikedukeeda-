@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="statesContainer">
                <form class="" name="editStateFrm" id="editStateFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editStateSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editStateErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State Name</label>
                            <input id="stateName" type="text" class="form-control" name="stateName" value="{{$state_data->state_name}}" >
                            <div class="invalid-feedback" id="error_validation_stateName"></div>
                            <input id="state_id" type="hidden" name="state_id" value="{{$state_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="stateCountry" class="form-control" name="stateCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $state_data->country_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_stateCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="stateStatus" class="form-control" name="stateStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($state_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($state_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_stateStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="state_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="state_edit_cancel" name="state_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('states/list')}}'">Cancel</button>
                           <button type="button" id ="state_edit_submit" name="state_edit_submit" class="btn btn-dialog" onclick="submitEditState();">Submit</button>
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
