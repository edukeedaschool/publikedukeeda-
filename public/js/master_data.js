"use strict";

function submitAddState(){
    $("#addStateErrorMessage,#addStateSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addStateFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/state/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addStateErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addStateSuccessMessage").html(msg.message).show();
                    $("#addStateErrorMessage,.invalid-feedback").html('').hide();
                    $("#stateName").val('');
                    var url = ROOT_PATH+"/states/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addStateErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addStateErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditState(){
    $("#editStateErrorMessage,#editStateSuccessMessage,.invalid-feedback").html('').hide();
    var state_id = $("#state_id").val();
    var form_data = $("#editStateFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/state/edit/"+state_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editStateErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editStateSuccessMessage").html(msg.message).show();
                    $("#editStateErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/states/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editStateErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editStateErrorMessage").html('Error in processing request').show();
        }
    });
}


function submitAddDistrict(){
    $("#addDistrictErrorMessage,#addDistrictSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addDistrictFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/district/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addDistrictErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addDistrictSuccessMessage").html(msg.message).show();
                    $("#addDistrictErrorMessage,.invalid-feedback").html('').hide();
                    $("#districtName").val('');
                    var url = ROOT_PATH+"/districts/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addDistrictErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addDistrictErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditDistrict(){
    $("#editDistrictErrorMessage,#editDistrictSuccessMessage,.invalid-feedback").html('').hide();
    var district_id = $("#district_id").val();
    var form_data = $("#editDistrictFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/district/edit/"+district_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editDistrictErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editDistrictSuccessMessage").html(msg.message).show();
                    $("#editDistrictErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/districts/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editDistrictErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editDistrictErrorMessage").html('Error in processing request').show();
        }
    });
}


function submitAddMC1(){
    $("#addMCErrorMessage,#addMCSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addMCFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/mc1/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addMCErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addMCSuccessMessage").html(msg.message).show();
                    $("#addMCErrorMessage,.invalid-feedback").html('').hide();
                    $("#mcName").val('');
                    var url = ROOT_PATH+"/mc1/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addMCErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addMCErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditMC1(){
    $("#editMCErrorMessage,#editMCSuccessMessage,.invalid-feedback").html('').hide();
    var mc_id = $("#mc_id").val();
    var form_data = $("#editMCFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/mc1/edit/"+mc_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editMCErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editMCSuccessMessage").html(msg.message).show();
                    $("#editMCErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/mc1/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editMCErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editMCErrorMessage").html('Error in processing request').show();
        }
    });
}

function getDistrictList(state_id,select_elem,error_elem,sel_val){
    var str = '<option value="">District</option>';
    
    if(state_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/districts/listing/"+state_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var districts = msg.district_list, sel = '';
                    for(var i=0;i<districts.length;i++){
                        sel = (sel_val == districts[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+districts[i].id+'">'+districts[i].district_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function submitAddMC2(){
    $("#addMCErrorMessage,#addMCSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addMCFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/mc2/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addMCErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addMCSuccessMessage").html(msg.message).show();
                    $("#addMCErrorMessage,.invalid-feedback").html('').hide();
                    $("#mcName").val('');
                    var url = ROOT_PATH+"/mc2/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addMCErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addMCErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditMC2(){
    $("#editMCErrorMessage,#editMCSuccessMessage,.invalid-feedback").html('').hide();
    var mc_id = $("#mc_id").val();
    var form_data = $("#editMCFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/mc2/edit/"+mc_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editMCErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editMCSuccessMessage").html(msg.message).show();
                    $("#editMCErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/mc2/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editMCErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editMCErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddCityCouncil(){
    $("#addCityCouncilErrorMessage,#addCityCouncilSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addCityCouncilFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/city-council/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addCityCouncilErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addCityCouncilSuccessMessage").html(msg.message).show();
                    $("#addCityCouncilErrorMessage,.invalid-feedback").html('').hide();
                    $("#ccName").val('');
                    var url = ROOT_PATH+"/city-council/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addCityCouncilErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addCityCouncilErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditCityCouncil(){
    $("#editCityCouncilErrorMessage,#editCityCouncilSuccessMessage,.invalid-feedback").html('').hide();
    var cc_id = $("#cc_id").val();
    var form_data = $("#editCityCouncilFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/city-council/edit/"+cc_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editCityCouncilErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editCityCouncilSuccessMessage").html(msg.message).show();
                    $("#editCityCouncilErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/city-council/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editCityCouncilErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editCityCouncilErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddBlock(){
    $("#addBlockErrorMessage,#addBlockSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addBlockFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/block/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addBlockErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addBlockSuccessMessage").html(msg.message).show();
                    $("#addBlockErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/block/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addBlockErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addBlockErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditBlock(){
    $("#editBlockErrorMessage,#editBlockSuccessMessage,.invalid-feedback").html('').hide();
    var block_id = $("#block_id").val();
    var form_data = $("#editBlockFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/block/edit/"+block_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editBlockErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editBlockSuccessMessage").html(msg.message).show();
                    $("#editBlockErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/block/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editBlockErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editBlockErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddSubDistrict(){
    $("#addSubDistrictErrorMessage,#addSubDistrictSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addSubDistrictFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/sub-district/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addSubDistrictErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addSubDistrictSuccessMessage").html(msg.message).show();
                    $("#addSubDistrictErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/sub-district/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addSubDistrictErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addSubDistrictErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditSubDistrict(){
    $("#editSubDistrictErrorMessage,#editSubDistrictSuccessMessage,.invalid-feedback").html('').hide();
    var sd_id = $("#sd_id").val();
    var form_data = $("#editSubDistrictFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/sub-district/edit/"+sd_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editSubDistrictErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editSubDistrictSuccessMessage").html(msg.message).show();
                    $("#editSubDistrictErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/sub-district/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editSubDistrictErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editSubDistrictErrorMessage").html('Error in processing request').show();
        }
    });
}

function getMC1List(state_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Municipal Corporation</option>';
    
    if(state_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/mc1/listing/"+state_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var mc1 = msg.mc1_list,sel = '';
                    for(var i=0;i<mc1.length;i++){
                        sel = (sel_val == mc1[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+mc1[i].id+'">'+mc1[i].mc_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function getMC2List(district_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Municipality</option>';
    
    if(district_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/mc2/listing/"+district_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var mc2 = msg.mc2_list,sel = '';
                    for(var i=0;i<mc2.length;i++){
                        sel = (sel_val == mc2[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+mc2[i].id+'">'+mc2[i].mc_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}



function getCityCouncilList(district_id,select_elem,error_elem,sel_val){
    var str = '<option value="">City Council</option>';
    
    if(district_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/city-council/listing/"+district_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var cc = msg.cc_list,sel = '';
                    for(var i=0;i<cc.length;i++){
                        sel = (sel_val == cc[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+cc[i].id+'">'+cc[i].city_council_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function getSubDistrictList(district_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Sub District</option>';
    
    if(district_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/sub-district/listing/"+district_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var sub_districts = msg.sub_district_list,sel = '';
                    for(var i=0;i<sub_districts.length;i++){
                        sel = (sel_val == sub_districts[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+sub_districts[i].id+'">'+sub_districts[i].sub_district_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function getBlockList(district_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Block</option>';
    
    if(district_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/block/listing/"+district_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var blocks = msg.block_list,sel = '';
                    for(var i=0;i<blocks.length;i++){
                        sel = (sel_val == blocks[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+blocks[i].id+'">'+blocks[i].block_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function getVillageList(sub_district_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Village</option>';
    
    if(sub_district_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/village/listing/"+sub_district_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var villages = msg.village_list,sel = '';
                    for(var i=0;i<villages.length;i++){
                        sel = (sel_val == villages[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+villages[i].id+'">'+villages[i].village_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function getWardList(city_council_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Ward</option>';
    
    if(city_council_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/ward/listing/"+city_council_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var wards = msg.ward_list,sel = '';
                    for(var i=0;i<wards.length;i++){
                        sel = (sel_val == wards[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+wards[i].id+'">'+wards[i].ward_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function getLACList(district_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Legislative Assembly Constituency</option>';
    
    if(district_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/lac/listing/"+district_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var lac_list = msg.lac_list,sel = '';
                    for(var i=0;i<lac_list.length;i++){
                        sel = (sel_val == lac_list[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+lac_list[i].id+'">'+lac_list[i].constituency_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function getPCList(district_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Parliamentary Constituency</option>';
    
    if(district_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/pc/listing/"+district_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var pc_list = msg.pc_list,sel = '';
                    for(var i=0;i<pc_list.length;i++){
                        sel = (sel_val == pc_list[i].id)?'selected':'';
                        str+='<option '+sel+' value="'+pc_list[i].id+'">'+pc_list[i].constituency_name+'</option>';
                    }
                    
                    $("#"+select_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function submitAddWard(){
    $("#addWardErrorMessage,#addWardSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addWardFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/ward/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addWardErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addWardSuccessMessage").html(msg.message).show();
                    $("#addWardErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/ward/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addWardErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addWardErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditWard(){
    $("#editWardErrorMessage,#editWardSuccessMessage,.invalid-feedback").html('').hide();
    var ward_id = $("#ward_id").val();
    var form_data = $("#editWardFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/ward/edit/"+ward_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editWardErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editWardSuccessMessage").html(msg.message).show();
                    $("#editWardErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/ward/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editWardErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editWardErrorMessage").html('Error in processing request').show();
        }
    });
}


function submitAddVillage(){
    $("#addVillageErrorMessage,#addVillageSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addVillageFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/village/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addVillageErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addVillageSuccessMessage").html(msg.message).show();
                    $("#addVillageErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/village/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addVillageErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addVillageErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditVillage(){
    $("#editVillageErrorMessage,#editVillageSuccessMessage,.invalid-feedback").html('').hide();
    var village_id = $("#village_id").val();
    var form_data = $("#editVillageFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/village/edit/"+village_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editVillageErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editVillageSuccessMessage").html(msg.message).show();
                    $("#editVillageErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/village/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editVillageErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editVillageErrorMessage").html('Error in processing request').show();
        }
    });
}


function submitAddPostalCode(){
    $("#addPostalCodeErrorMessage,#addPostalCodeSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addPostalCodeFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/postal-code/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addPostalCodeErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addPostalCodeSuccessMessage").html(msg.message).show();
                    $("#addPostalCodeErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/postal-code/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addPostalCodeErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addPostalCodeErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditPostalCode(){
    $("#editPostalCodeErrorMessage,#editPostalCodeSuccessMessage,.invalid-feedback").html('').hide();
    var pc_id = $("#pc_id").val();
    var form_data = $("#editPostalCodeFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/postal-code/edit/"+pc_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editPostalCodeErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editPostalCodeSuccessMessage").html(msg.message).show();
                    $("#editPostalCodeErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/postal-code/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editPostalCodeErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editPostalCodeErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddPoliticalParty(){
    $("#addPoliticalPartyFrm").submit();
}

$("#addPoliticalPartyFrm").on('submit', function(event){
    event.preventDefault(); 
    
    var formData = new FormData(this);
    
    $("#pp_add_spinner").show();
    $("#pp_add_submit,#pp_add_cancel").attr('disabled',true);
    $(".invalid-feedback,#editPoliticalPartyErrorMessage,#editPoliticalPartySuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/political-party/add",
        success:function(msg){
            $("#pp_add_spinner").hide();
            $("#pp_add_submit,#pp_add_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#addPoliticalPartyErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#addPoliticalPartySuccessMessage").html(msg.message).show();
                $("#addPoliticalPartyErrorMessage,.invalid-feedback").html('').hide();
                var url = ROOT_PATH+"/political-party/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#addPoliticalPartyErrorMessage").html('Error in processing request').show();
            $("#pp_add_spinner").hide();
            $("#pp_add_submit,#pp_add_cancel").attr('disabled',false);
        }
    });
});


function submitEditPoliticalParty(){
    $("#editPoliticalPartyFrm").submit();
}

$("#editPoliticalPartyFrm").on('submit', function(event){
    event.preventDefault(); 
    
    var formData = new FormData(this);
    var pp_id = $("#pp_id").val();
    $("#pp_edit_spinner").show();
    $("#pp_edit_submit,#pp_edit_cancel").attr('disabled',true);
    $(".invalid-feedback,#editPoliticalPartyErrorMessage,#editPoliticalPartySuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/political-party/edit/"+pp_id,
        success:function(msg){
            $("#pp_edit_spinner").hide();
            $("#pp_edit_submit,#pp_edit_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#editPoliticalPartyErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#editPoliticalPartySuccessMessage").html(msg.message).show();
                $("#editPoliticalPartyErrorMessage,.invalid-feedback").html('').hide();
                var url = ROOT_PATH+"/political-party/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#editPoliticalPartyErrorMessage").html('Error in processing request').show();
            $("#pp_edit_spinner").hide();
            $("#pp_edit_submit,#pp_edit_cancel").attr('disabled',false);
        }
    });
});


function getDistrictListChk(state_id,div_elem,chk_elem,error_elem,sel_val){
    var str = '';
    
    if(state_id == ''){ 
        $("#"+div_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/districts/listing/"+state_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+error_elem).html(errors).show();
                    } 
                }else{ 
                    var districts = msg.district_list, chk = '';
                    str = '<table class="table  clearfix"><tr>';
                    var sel_val_arr = sel_val.split(',');
                    
                    for(var i=0;i<sel_val_arr.length;i++){
                        sel_val_arr[i] = parseInt(sel_val_arr[i]);
                    }
                    
                    for(var i=0;i<districts.length;i++){
                        chk = (sel_val_arr.indexOf(districts[i].id) >= 0)?'checked':''; 
                        str+='<td style="border-top:none;"><input type="checkbox" name="'+chk_elem+'[]" id="'+chk_elem+'" '+chk+' value="'+districts[i].id+'"> '+districts[i].district_name+"</td>";
                        if(i > 0 && (i+1)%3 == 0){
                            str+='</tr><tr>';
                        }
                    }
                    
                    str+='</tr></table>';
                    
                    $("#"+div_elem).html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function submitAddLAConstituency(){
    $("#addConstituencyErrorMessage,#addConstituencySuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addConstituencyFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/la-constituency/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addConstituencyErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addConstituencySuccessMessage").html(msg.message).show();
                    $("#addConstituencyErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/la-constituency/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addConstituencyErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addConstituencyErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditLAConstituency(){
    $("#editConstituencyErrorMessage,#editConstituencySuccessMessage,.invalid-feedback").html('').hide();
    var constituency_id = $("#constituency_id").val();
    var form_data = $("#editConstituencyFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/la-constituency/edit/"+constituency_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editConstituencyErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editConstituencySuccessMessage").html(msg.message).show();
                    $("#editConstituencyErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/la-constituency/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editConstituencyErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editConstituencyErrorMessage").html('Error in processing request').show();
        }
    });
}


function submitAddPAConstituency(){
    $("#addConstituencyErrorMessage,#addConstituencySuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addConstituencyFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/pa-constituency/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addConstituencyErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addConstituencySuccessMessage").html(msg.message).show();
                    $("#addConstituencyErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/pa-constituency/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addConstituencyErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addConstituencyErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditPAConstituency(){
    $("#editConstituencyErrorMessage,#editConstituencySuccessMessage,.invalid-feedback").html('').hide();
    var constituency_id = $("#constituency_id").val();
    var form_data = $("#editConstituencyFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/pa-constituency/edit/"+constituency_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editConstituencyErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editConstituencySuccessMessage").html(msg.message).show();
                    $("#editConstituencyErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/pa-constituency/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editConstituencyErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editConstituencyErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddDepartment(){
    $("#addDepartmentFrm").submit();
}

$("#addDepartmentFrm").on('submit', function(event){
    event.preventDefault(); 
    var formData = new FormData(this);
    
    $("#department_add_spinner").show();
    $("#department_add_submit,#department_add_cancel").attr('disabled',true);
    $(".invalid-feedback,#editDepartmentErrorMessage,#editDepartmentSuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/govt-department/add",
        success:function(msg){
            $("#department_add_spinner").hide();
            $("#department_add_submit,#department_add_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#addDepartmentErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#addDepartmentSuccessMessage").html(msg.message).show();
                $("#addDepartmentErrorMessage,.invalid-feedback").html('').hide();
                var url = ROOT_PATH+"/govt-department/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#addDepartmentErrorMessage").html('Error in processing request').show();
            $("#department_add_spinner").hide();
            $("#department_add_submit,#department_add_cancel").attr('disabled',false);
        }
    });
});

function toggleDepartmentTypeData(val){
    $("#countryDiv,#stateDiv,#otherTypeNameDiv").hide();
    
    if(val == 'national'){
        $("#countryDiv").show();
    }
    
    if(val == 'state'){
        $("#countryDiv,#stateDiv").show();
    }
    
    if(val == 'other'){
        $("#otherTypeNameDiv").show();
    }
}


function submitEditDepartment(){
    $("#editDepartmentFrm").submit();
}

$("#editDepartmentFrm").on('submit', function(event){
    event.preventDefault(); 
    var formData = new FormData(this);
    var department_id = $("#department_id").val();
    
    $("#department_edit_spinner").show();
    $("#department_edit_submit,#department_edit_cancel").attr('disabled',true);
    $(".invalid-feedback,#editDepartmentErrorMessage,#editDepartmentSuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/govt-department/edit/"+department_id,
        success:function(msg){
            $("#department_edit_spinner").hide();
            $("#department_edit_submit,#department_edit_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#editDepartmentErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#editDepartmentSuccessMessage").html(msg.message).show();
                $("#editDepartmentErrorMessage,.invalid-feedback").html('').hide();
                var url = ROOT_PATH+"/govt-department/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#editDepartmentErrorMessage").html('Error in processing request').show();
            $("#department_edit_spinner").hide();
            $("#department_edit_submit,#department_edit_cancel").attr('disabled',false);
        }
    });
});

function toggleOrganizationTypeData(val){
    $("#otherTypeNameDiv").hide();
    
    if(val == 'other'){
        $("#otherTypeNameDiv").show();
    }
}


function submitAddOrganization(){
    $("#addOrganizationFrm").submit();
}

$("#addOrganizationFrm").on('submit', function(event){
    event.preventDefault(); 
    var formData = new FormData(this);
    
    $("#organization_add_spinner").show();
    $("#organization_add_submit,#organization_add_cancel").attr('disabled',true);
    $(".invalid-feedback,#editOrganizationErrorMessage,#editOrganizationSuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/non-profit-organization/add",
        success:function(msg){
            $("#organization_add_spinner").hide();
            $("#organization_add_submit,#organization_add_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                if(errors != ''){
                    $("#addOrganizationErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#addOrganizationSuccessMessage").html(msg.message).show();
                $("#addOrganizationErrorMessage,.invalid-feedback").html('').hide();
                var url = ROOT_PATH+"/non-profit-organization/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#addOrganizationErrorMessage").html('Error in processing request').show();
            $("#organization_add_spinner").hide();
            $("#organization_add_submit,#organization_add_cancel").attr('disabled',false);
        }
    });
});


function submitEditOrganization(){
    $("#editOrganizationFrm").submit();
}

$("#editOrganizationFrm").on('submit', function(event){
    event.preventDefault(); 
    var formData = new FormData(this);
    var organization_id = $("#organization_id").val();
    
    $("#organization_edit_spinner").show();
    $("#organization_edit_submit,#organization_edit_cancel").attr('disabled',true);
    $(".invalid-feedback,#editOrganizationErrorMessage,#editOrganizationSuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/non-profit-organization/edit/"+organization_id,
        success:function(msg){
            $("#organization_edit_spinner").hide();
            $("#organization_edit_submit,#organization_edit_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#editOrganizationErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#editOrganizationSuccessMessage").html(msg.message).show();
                $("#editOrganizationErrorMessage,.invalid-feedback").html('').hide();
                var url = ROOT_PATH+"/non-profit-organization/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#editOrganizationErrorMessage").html('Error in processing request').show();
            $("#organization_edit_spinner").hide();
            $("#organization_edit_submit,#organization_edit_cancel").attr('disabled',false);
        }
    });
});

function submitAddElectedOfficialPosition(){
    $("#addPositionErrorMessage,#addPositionSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addPositionFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/elected-official-position/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addPositionErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addPositionSuccessMessage").html(msg.message).show();
                    $("#addPositionErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/elected-official-position/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addPositionErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addPositionErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditElectedOfficialPosition(){
    $("#editPositionErrorMessage,#editPositionSuccessMessage,.invalid-feedback").html('').hide();
    var position_id = $("#position_id").val();
    var form_data = $("#editPositionFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/elected-official-position/edit/"+position_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editPositionErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editPositionSuccessMessage").html(msg.message).show();
                    $("#editPositionErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/elected-official-position/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editPositionErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editPositionErrorMessage").html('Error in processing request').show();
        }
    });
}


function submitAddPoliticalPartyOfficialPosition(){
    $("#addPositionErrorMessage,#addPositionSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addPositionFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/political-party-official-position/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addPositionErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addPositionSuccessMessage").html(msg.message).show();
                    $("#addPositionErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/political-party-official-position/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addPositionErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addPositionErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditPoliticalPartyOfficialPosition(){
    $("#editPositionErrorMessage,#editPositionSuccessMessage,.invalid-feedback").html('').hide();
    var position_id = $("#position_id").val();
    var form_data = $("#editPositionFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/political-party-official-position/edit/"+position_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editPositionErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editPositionSuccessMessage").html(msg.message).show();
                    $("#editPositionErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/political-party-official-position/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editPositionErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editPositionErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddGroup(){
    $("#addGroupErrorMessage,#addGroupSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addGroupFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/group/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addGroupErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addGroupSuccessMessage").html(msg.message).show();
                    $("#addGroupErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/group/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addGroupErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addGroupErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditGroup(){
    $("#editGroupErrorMessage,#editGroupSuccessMessage,.invalid-feedback").html('').hide();
    var group_id = $("#group_id").val();
    var form_data = $("#editGroupFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/group/edit/"+group_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editGroupErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editGroupSuccessMessage").html(msg.message).show();
                    $("#editGroupErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/group/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editGroupErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editGroupErrorMessage").html('Error in processing request').show();
        }
    });
}


function submitAddSubGroup(){
    $("#addSubGroupErrorMessage,#addSubGroupSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addSubGroupFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/sub-group/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addSubGroupErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addSubGroupSuccessMessage").html(msg.message).show();
                    $("#addSubGroupErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/sub-group/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addSubGroupErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addSubGroupErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditSubGroup(){
    $("#editSubGroupErrorMessage,#editSubGroupSuccessMessage,.invalid-feedback").html('').hide();
    var sub_group_id = $("#sub_group_id").val();
    var form_data = $("#editSubGroupFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/sub-group/edit/"+sub_group_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editSubGroupErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editSubGroupSuccessMessage").html(msg.message).show();
                    $("#editSubGroupErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/sub-group/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editSubGroupErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editSubGroupErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddSubmissionPurpose(){
    $("#addSubmissionPurposeErrorMessage,#addSubmissionPurposeSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addSubmissionPurposeFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/submission-purpose/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addSubmissionPurposeErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addSubmissionPurposeSuccessMessage").html(msg.message).show();
                    $("#addSubmissionPurposeErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/submission-purpose/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addSubmissionPurposeErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addSubmissionPurposeErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditSubmissionPurpose(){
    $("#editSubmissionPurposeErrorMessage,#editSubmissionPurposeSuccessMessage,.invalid-feedback").html('').hide();
    var sub_purpose_id = $("#sub_purpose_id").val();
    var form_data = $("#editSubmissionPurposeFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/submission-purpose/edit/"+sub_purpose_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editSubmissionPurposeErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editSubmissionPurposeSuccessMessage").html(msg.message).show();
                    $("#editSubmissionPurposeErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/submission-purpose/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editSubmissionPurposeErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editSubmissionPurposeErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddSubmissionType(){
    $("#addSubmissionTypeErrorMessage,#addSubmissionTypeSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addSubmissionTypeFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/submission-type/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addSubmissionTypeErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addSubmissionTypeSuccessMessage").html(msg.message).show();
                    $("#addSubmissionTypeErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/submission-type/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addSubmissionTypeErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addSubmissionTypeErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditSubmissionType(){
    $("#editSubmissionTypeErrorMessage,#editSubmissionTypeSuccessMessage,.invalid-feedback").html('').hide();
    var sub_type_id = $("#sub_type_id").val();
    var form_data = $("#editSubmissionTypeFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/submission-type/edit/"+sub_type_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editSubmissionTypeErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editSubmissionTypeSuccessMessage").html(msg.message).show();
                    $("#editSubmissionTypeErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/submission-type/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editSubmissionTypeErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editSubmissionTypeErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddReviewLevel(){
    $("#addReviewLevelErrorMessage,#addReviewLevelSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addReviewLevelFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/review-level/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addReviewLevelErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addReviewLevelSuccessMessage").html(msg.message).show();
                    $("#addReviewLevelErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/review-level/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addReviewLevelErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addReviewLevelErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditReviewLevel(){
    $("#editReviewLevelErrorMessage,#editReviewLevelSuccessMessage,.invalid-feedback").html('').hide();
    var review_level_id = $("#review_level_id").val();
    var form_data = $("#editReviewLevelFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/review-level/edit/"+review_level_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editReviewLevelErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editReviewLevelSuccessMessage").html(msg.message).show();
                    $("#editReviewLevelErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/review-level/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editReviewLevelErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editReviewLevelErrorMessage").html('Error in processing request').show();
        }
    });
}

function updateBulkData(action,type){
    var chk_vals = [];
    
    $("."+type+"-id-chk").each(function (){
        if($(this).is(":checked")){
            chk_vals.push($(this).val());
        }
    });
    
    chk_vals = chk_vals.join(',');
    
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/master-data/bulk/update",
        method:"POST",
        data:{ids:chk_vals,action:action,type:type},
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#"+type+"UpdateErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#"+type+"UpdateSuccessMessage").html(msg.message).show();
                    $("#"+type+"UpdateErrorMessage").html('').hide();
                    //document.getElementById("#"+type+"UpdateSuccessMessage").scrollIntoView();
                    setTimeout(function(){  window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,"#"+type+"UpdateErrorMessage");
            }
        },error:function(obj,status,error){
            $("#"+type+"UpdateErrorMessage").html('Error in processing request').show();
        }
    });
}