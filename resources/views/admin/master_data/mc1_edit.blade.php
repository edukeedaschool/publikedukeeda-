@extends('layouts.default')
@section('content')

    <section class="product_area">
        <div class="container-fluid" >

            <div class="separator-10">&nbsp;</div>

            <div id="mcsContainer">
                <form class="" name="editMCFrm" id="editMCFrm" type="POST">
                    
                    <div class="alert alert-success alert-dismissible elem-hidden"  id="editMCSuccessMessage"></div>
                    <div class="alert alert-danger alert-dismissible elem-hidden"  id="editMCErrorMessage"></div>

                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>MC Name</label>
                            <input id="mcName" type="text" class="form-control" name="mcName" value="{{$mc_data->mc_name}}" >
                            <div class="invalid-feedback" id="error_validation_mcName"></div>
                            <input id="mc_id" type="hidden" name="mc_id" value="{{$mc_data->id}}" >
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Country</label>
                            <select id="mcCountry" class="form-control" name="mcCountry" >
                                <option value="">Country</option>
                                @for($i=0;$i<count($country_list);$i++)
                                    <?php $sel = ($country_list[$i]['id'] == $state_data->country_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$country_list[$i]['id']}}">{{$country_list[$i]['country_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_mcCountry"></div>
                        </div>
                    </div>    
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>State</label>
                            <select id="mcState" class="form-control" name="mcState" >
                                <option value="">State</option>
                                @for($i=0;$i<count($states_list);$i++)
                                    <?php $sel = ($states_list[$i]['id'] == $mc_data->state_id)?'selected':''; ?>
                                    <option {{$sel}} value="{{$states_list[$i]['id']}}">{{$states_list[$i]['state_name']}}</option>
                                @endfor    
                            </select>    
                            <div class="invalid-feedback" id="error_validation_mcState"></div>
                        </div>
                    </div>   
                    
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <label>Status</label>
                            <select id="mcStatus" class="form-control" name="mcStatus" >
                                <option value="">Status</option>
                                <option value="1" @if($mc_data->status == 1) selected @endif>Enabled</option>
                                <option value="0" @if($mc_data->status == 0) selected @endif>Disabled</option>
                            </select>    
                            <div class="invalid-feedback" id="error_validation_mcStatus"></div>
                        </div>
                    </div>    
                    
                    <div class="separator-10">&nbsp;</div>
    
                    <div class="form-row ">
                        <div class="separator-10">&nbsp;</div>
                        <div class="form-group col-md-6" style="text-align:center; ">
                            <div id="mc_edit_spinner" style="display:none;" class="spinner-border spinner-border-sm text-secondary" role="status"><span class="sr-only">Loading...</span></div>
                           <button type="button" id="mc_edit_cancel" name="mc_edit_cancel" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.href='{{url('mc1/list')}}'">Cancel</button>
                           <button type="button" id ="mc_edit_submit" name="mc_edit_submit" class="btn btn-dialog" onclick="submitEditMC1();">Submit</button>
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
