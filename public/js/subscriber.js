"use strict";

function getOfficeBelongsToData(val){
    $(".toggle-div").hide();
    $(".pp-rep-area-field,.eo-rep-area-field").hide();
    
    if(val == ''){
        return false;
    }
    
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/office-belongs-to-data/"+val,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addSubscriberErrorMessage").html(errors).show();
                    } 
                }else{ 
                    var group_data = msg.group_data;
                    if(group_data.group_type == 'political'){
                        $("#politicalParty_div").show();
                    }
                    
                    if(group_data.group_type == 'political' && group_data.group_sub_type == 'person'){
                        $("#gender_div,#DOB_div,#politicalParty_div,#politicalPartyOfficialPosition_div,#repAreaOfficialPartyPosition_div,#electedOfficialPositionName_div,#repAreaElectedOfficialPosition_div").show();
                    }
                    
                    if(group_data.group_type == 'non_political' && group_data.group_sub_type == 'person'){
                        $("#gender_div,#DOB_div,#keyIdentity2_div,#organizationName_div").show();
                    }
                    
                    if(group_data.group_sub_type == 'government_department' || group_data.group_sub_type =='nonprofit_organization'){
                        $("#authorizedPersonName_div,#authorizedPersonDesignation_div").show();
                    }
                }
            }else{
                displayResponseError(msg,'addSubscriberErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addSubscriberErrorMessage").html('Error in processing request').show();
        }
    });
}

function getLocList(){
    var loc_list = [];
    loc_list['country'] = 'country'; loc_list['state'] = 'country,state'; loc_list['district'] = 'country,state,district';  loc_list['sub_district'] = 'country,state,district,subDistrict';
    loc_list['legislative_assembly_constituency'] = 'country,state,district,LAC';loc_list['parliamentary_constituency'] = 'country,state,district,PC';
    loc_list['municipal_corporation'] = 'country,state,MC1';loc_list['municipality'] = 'country,state,district,MC2';loc_list['city_council'] = 'country,state,district,CC';
    loc_list['block'] = 'country,state,district,block';loc_list['ward'] = 'country,state,district,CC,ward';loc_list['village'] = 'country,state,district,subDistrict,village';
    
    return loc_list;
}

function toggleRepAreaFields(rep_area,type){
    $("."+type+"-rep-area-field").hide();
    
    var loc_list = getLocList(), field = '';
    //alert(rep_area);
    if(rep_area != ''){
        var fields = loc_list[rep_area];
        fields = fields.split(',');
        for(var i=0;i<fields.length;i++){
            field = fields[i];
            $("#"+field+"_"+type+"_div").show();
        }
    }
}

function updateSubscriberFields(val,field,pos_type,sel_val){
    var rep_area =  (pos_type == 'pp')?$("#repAreaOfficialPartyPosition").val():$("#repAreaElectedOfficialPosition").val();
    
    if(field == 'state'){
        getDistrictList(val,'district_'+pos_type,'addSubscriberErrorMessage',sel_val);
        
        getMC1List(val,'MC1_'+pos_type,'addSubscriberErrorMessage',sel_val);
    }
    
    if(field == 'district'){
        if(rep_area == 'sub_district' || rep_area == 'village'){
            getSubDistrictList(val,'subDistrict_'+pos_type,'addSubscriberErrorMessage',sel_val);
        }
        
        if(rep_area == 'municipality'){
            getMC2List(val,'MC2_'+pos_type,'addSubscriberErrorMessage',sel_val);
        }
        
        if(rep_area == 'city_council' || rep_area == 'ward'){
            getCityCouncilList(val,'CC_'+pos_type,'addSubscriberErrorMessage',sel_val);
        }
        
        if(rep_area == 'block'){
            getBlockList(val,'block_'+pos_type,'addSubscriberErrorMessage',sel_val);
        }
        
        if(rep_area == 'legislative_assembly_constituency'){
            getLACList(val,'LAC_'+pos_type,'addSubscriberErrorMessage',sel_val);
        }
        
        if(rep_area == 'parliamentary_constituency'){
            getPCList(val,'PC_'+pos_type,'addSubscriberErrorMessage',sel_val);
        }
    }
    
    if(field == 'sub_district'){
        getVillageList(val,'village_'+pos_type,'addSubscriberErrorMessage',sel_val);
    }
    
    if(field == 'city_council'){
        getWardList(val,'ward_'+pos_type,'addSubscriberErrorMessage',sel_val);
    }
}

function toggleOfficialPosData(){
    if($("#politicalPartyOfficialPosition").val() == "0" && $("#electedOfficialPositionName").val() == "0"){
        $("#keyIdentity1_div").show();
    }else{
        $("#keyIdentity1_div").hide();
    }
}

function submitAddSubscriber(){
    $("#addSubscriberFrm").submit();
}

$("#addSubscriberFrm").on('submit', function(event){
    event.preventDefault(); 
    
    var formData = new FormData(this);
    
    $("#subscriber_add_spinner").show();
    $("#subscriber_add_submit,#subscriber_add_cancel").attr('disabled',true);
    $(".invalid-feedback,#addSubscriberErrorMessage,#addSubscriberSuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/subscriber/add",
        success:function(msg){
            $("#subscriber_add_spinner").hide();
            $("#subscriber_add_submit,#subscriber_add_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#addSubscriberErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#addSubscriberSuccessMessage").html(msg.message).show();
                $("#addSubscriberErrorMessage,.invalid-feedback").html('').hide();
                document.getElementById("addSubscriberSuccessMessage").scrollIntoView();
                var url = ROOT_PATH+"/subscriber/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#addSubscriberErrorMessage").html('Error in processing request').show();
            $("#subscriber_add_spinner").hide();
            $("#subscriber_add_submit,#subscriber_add_cancel").attr('disabled',false);
        }
    });
});

function updatePostalCodeData(postal_code){
    $("#postalCodeError").html('').hide();
    
    if(postal_code.length > 5){
        ajaxSetup();

        $.ajax({
            url:ROOT_PATH+"/postal-code/data/"+postal_code,
            method:"GET",
            data:'',
            success:function(msg){
                if(objectPropertyExists(msg,'status')){
                    if(msg.status == 'fail'){
                        var errors = getResponseErrors(msg,'<br/>','error_validation_');
                        if(errors != ''){
                            $("#addSubscriberErrorMessage").html(errors).show();
                        } 
                    }else{ 
                        var postal_code_data = msg.postal_code_data, str = '', sel = '';
                        
                        var country_list = msg.country_list, str = '<option value="">Country</option>';
                        for(var i=0;i<country_list.length;i++){
                            sel = (1 == country_list[i].id)?'selected':'';
                            str+='<option '+sel+' value="'+country_list[i].id+'">'+country_list[i].country_name+'</option>';
                        }

                        $("#country").html(str);
                            
                        if(postal_code_data == null || postal_code_data == ''){
                            $("#postalCodeError").html('Postal Code does not exists').show();
                            var state_list = msg.state_list, str = '<option value="">State</option>';
                            for(var i=0;i<state_list.length;i++){
                                str+='<option value="'+state_list[i].id+'">'+state_list[i].state_name+'</option>';
                            }

                            $("#state").html(str);
                            
                            $("#district").html('<option value="">District</option>');
                            $("#subDistrict").html('<option value="">Sub District</option>');
                            $("#village").html('<option value="">Village</option>');
                        }else{
                           
                            var state_list = msg.state_list, str = '<option value="">State</option>';
                            for(var i=0;i<state_list.length;i++){
                                sel = (postal_code_data.state_id == state_list[i].id)?'selected':'';
                                str+='<option '+sel+' value="'+state_list[i].id+'">'+state_list[i].state_name+'</option>';
                            }

                            $("#state").html(str);
                            
                            var district_list = msg.district_list, str = '<option value="">District</option>';
                            for(var i=0;i<district_list.length;i++){
                                sel = (postal_code_data.district_id == district_list[i].id)?'selected':'';
                                str+='<option '+sel+' value="'+district_list[i].id+'">'+district_list[i].district_name+'</option>';
                            }

                            $("#district").html(str);
                            
                            var sub_district_list = msg.sub_district_list, str = '<option value="">Sub District</option>';
                            for(var i=0;i<sub_district_list.length;i++){
                                sel = (postal_code_data.sub_district_id == sub_district_list[i].id)?'selected':'';
                                str+='<option '+sel+' value="'+sub_district_list[i].id+'">'+sub_district_list[i].sub_district_name+'</option>';
                            }

                            $("#subDistrict").html(str);
                            
                            var village_list = msg.village_list, str = '<option value="">Village</option>';
                            for(var i=0;i<village_list.length;i++){
                                str+='<option value="'+village_list[i].id+'">'+village_list[i].village_name+'</option>';
                            }

                            $("#village").html(str);
                        }
                    }
                }else{
                    displayResponseError(msg,'addSubscriberErrorMessage');
                }
            },error:function(obj,status,error){
                $("#addSubscriberErrorMessage").html('Error in processing request').show();
            }
        });
    }
}


function submitEditSubscriber(){
    $("#editSubscriberFrm").submit();
}

$("#editSubscriberFrm").on('submit', function(event){
    event.preventDefault(); 
    
    var formData = new FormData(this);
    
    $("#subscriber_edit_spinner").show();
    $("#subscriber_edit_submit,#subscriber_edit_cancel").attr('disabled',true);
    $(".invalid-feedback,#addSubscriberErrorMessage,#addSubscriberSuccessMessage").html('').hide();
    var subscriber_id = $("#subscriber_id").val();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/subscriber/edit/"+subscriber_id,
        success:function(msg){
            $("#subscriber_edit_spinner").hide();
            $("#subscriber_edit_submit,#subscriber_edit_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#addSubscriberErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#addSubscriberSuccessMessage").html(msg.message).show();
                $("#addSubscriberErrorMessage,.invalid-feedback").html('').hide();
                document.getElementById("addSubscriberSuccessMessage").scrollIntoView();
                var url = ROOT_PATH+"/subscriber/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#addSubscriberErrorMessage").html('Error in processing request').show();
            $("#subscriber_edit_spinner").hide();
            $("#subscriber_edit_submit,#subscriber_edit_cancel").attr('disabled',false);
        }
    });
});

function updateSubscribers(action){
    var chk_vals = [];
    
    $(".sub-id-chk").each(function (){
        if($(this).is(":checked")){
            chk_vals.push($(this).val());
        }
    });
    
    if(chk_vals.length == 0){
        alert('Please select Subscribers');
        return false;
    }
    
    chk_vals = chk_vals.join(',');
    
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/subscriber/update",
        method:"POST",
        data:{ids:chk_vals,action:action},
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#subscriberErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#subscriberSuccessMessage").html(msg.message).show();
                    $("#subscriberErrorMessage,.invalid-feedback").html('').hide();
                    document.getElementById("subscriberSuccessMessage").scrollIntoView();
                    setTimeout(function(){  window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'subscriberErrorMessage');
            }
        },error:function(obj,status,error){
            $("#subscriberErrorMessage").html('Error in processing request').show();
        }
    });
}

function getNextDropdownReviewRange(val,pos){
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/subscriber/review-range/data/"+val,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#reviewDataErrorMessage").html(errors).show();
                    } 
                }else{ 
                    var review_ranges = msg.review_range_list, str = '<option value="">-- Review Range --</option>', elem_no = '';
                    for(var i=0;i<review_ranges.length;i++){
                        str+='<option value="'+review_ranges[i].id+'">'+review_ranges[i].review_range+'</option>';
                    }
                    
                    pos = parseInt(pos)
                    for(var i=pos+1;i<=20;i++){
                        if($(".review_range_"+i).length > 0){
                            elem_no = i;
                            break;
                        }
                    }
                    
                    if(elem_no != ''){
                        $(".review_range_"+elem_no).html(str);
                    }
                }
            }else{
                displayResponseError(msg,'reviewDataErrorMessage');
            }
        },error:function(obj,status,error){
            $("#reviewDataErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditSubscriberReviewData(){
    $("#reviewDataErrorMessage,#reviewDataSuccessMessage,.invalid-feedback").html('').hide();
    var mc_id = $("#mc_id").val();
    var form_data = $("#subscriberReviewDataFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/subscriber/review-data/edit",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#reviewDataErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#reviewDataSuccessMessage").html(msg.message).show();
                    $("#reviewDataErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/subscriber/review-data/view";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'reviewDataErrorMessage');
            }
        },error:function(obj,status,error){
            $("#reviewDataErrorMessage").html('Error in processing request').show();
        }
    });
}