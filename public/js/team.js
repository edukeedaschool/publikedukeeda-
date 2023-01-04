"use strict";

function submitAddDesignation(){
    $("#addDesignationErrorMessage,#addDesignationSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addDesignationFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/team-designation/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addDesignationErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addDesignationSuccessMessage").html(msg.message).show();
                    $("#addDesignationErrorMessage,.invalid-feedback").html('').hide();
                    
                    var url = ROOT_PATH+"/team-designation/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addDesignationErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addDesignationErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditDesignation(){
    $("#editDesignationErrorMessage,#editDesignationSuccessMessage,.invalid-feedback").html('').hide();
    var designation_id = $("#designation_id").val();
    var form_data = $("#editDesignationFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/team-designation/edit/"+designation_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editDesignationErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editDesignationSuccessMessage").html(msg.message).show();
                    $("#editDesignationErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/team-designation/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editDesignationErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editDesignationErrorMessage").html('Error in processing request').show();
        }
    });
}

function updateDesignations(action){
    var chk_vals = [];
    
    $(".des-id-chk").each(function (){
        if($(this).is(":checked")){
            chk_vals.push($(this).val());
        }
    });
    
    if(chk_vals.length == 0){
        alert('Please select Designation');
        return false;
    }
    
    chk_vals = chk_vals.join(',');
    
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/team-designation/update",
        method:"POST",
        data:{ids:chk_vals,action:action},
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#designationErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#designationSuccessMessage").html(msg.message).show();
                    $("#designationErrorMessage,.invalid-feedback").html('').hide();
                    document.getElementById("designationSuccessMessage").scrollIntoView();
                    setTimeout(function(){  window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'designationErrorMessage');
            }
        },error:function(obj,status,error){
            $("#designationErrorMessage").html('Error in processing request').show();
        }
    });
}

function displayTeamMemberAssignedArea(designation_id){
    $(".loc-div").hide();
    if(designation_id == '') return false;
    
    $.ajax({
        url:ROOT_PATH+"/team-designation/data/"+designation_id,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addTeamMemberErrorMessage").html(errors).show();
                    } 
                }else{ 
                    var designation_data = msg.designation_data;
                    var fields = designation_data.rep_area_fields,field = '';
                    fields = fields.split(',');
                    for(var i=0;i<fields.length;i++){
                        field = fields[i];
                        $("#"+field+"_div").show();
                    }
                }
            }else{
                displayResponseError(msg,'addTeamMemberErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addTeamMemberErrorMessage").html('Error in processing request').show();
        }
    });
}

function getDistrictFieldsData(val,pos_type,sel_val){
    getSubDistrictList(val,'subDistrict_'+pos_type,'addTeamMemberErrorMessage',sel_val);
    getMC2List(val,'MC2_'+pos_type,'addTeamMemberErrorMessage',sel_val);
    getCityCouncilList(val,'CC_'+pos_type,'addTeamMemberErrorMessage',sel_val);
    getBlockList(val,'block_'+pos_type,'addTeamMemberErrorMessage',sel_val);
    getLACList(val,'LAC_'+pos_type,'addTeamMemberErrorMessage',sel_val);
    getPCList(val,'PC_'+pos_type,'addTeamMemberErrorMessage',sel_val);
}

function getTeamMemberData(email){
    if(email == '') return false;
        
    $.ajax({
        url:ROOT_PATH+"/user/data/"+email,
        method:"GET",
        data:'',
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addTeamMemberErrorMessage").html(errors).show();
                    } 
                }else{ 
                    var user_data = msg.user_data;
                    if(user_data != null && user_data != ''){
                        $("#userName").val(user_data.name);
                        $("#mobileNumber").val(user_data.mobile_no);
                        $("#gender").val(user_data.gender);
                        $("#DOB").val(user_data.dob);
                        $("#user_Name").val(user_data.user_name);
                        $("#officialName").val(user_data.official_name);
                        $("#userName,#mobileNumber,#gender,#DOB,#officialName,#user_Name").attr('readonly',true);
                    }else{
                        $("#userName,#mobileNumber,#gender,#DOB,#officialName,#user_Name").val('').attr('readonly',false);
                    }
                }
            }else{
                displayResponseError(msg,'addTeamMemberErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addTeamMemberErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitAddTeamMember(){
    $("#addTeamMemberErrorMessage,#addTeamMemberSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addTeamMemberFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/team-member/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addTeamMemberErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addTeamMemberSuccessMessage").html(msg.message).show();
                    $("#addTeamMemberErrorMessage,.invalid-feedback").html('').hide();
                    
                    var url = ROOT_PATH+"/team-member/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addTeamMemberErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addTeamMemberErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditTeamMember(){
    $("#addTeamMemberErrorMessage,#addTeamMemberSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#editTeamMemberFrm").serialize();
    var tm_id = $("#tm_id").val();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/team-member/edit/"+tm_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addTeamMemberErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addTeamMemberSuccessMessage").html(msg.message).show();
                    $("#addTeamMemberErrorMessage,.invalid-feedback").html('').hide();
                    
                    var url = ROOT_PATH+"/team-member/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addTeamMemberErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addTeamMemberErrorMessage").html('Error in processing request').show();
        }
    });
}


function updateTeamMember(action){
    var chk_vals = [];
    
    $(".member-id-chk").each(function (){
        if($(this).is(":checked")){
            chk_vals.push($(this).val());
        }
    });
    
    if(chk_vals.length == 0){
        alert('Please select Team Member');
        return false;
    }
    
    chk_vals = chk_vals.join(',');
    
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/team-member/update",
        method:"POST",
        data:{ids:chk_vals,action:action},
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#memberErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#memberSuccessMessage").html(msg.message).show();
                    $("#memberErrorMessage,.invalid-feedback").html('').hide();
                    document.getElementById("memberSuccessMessage").scrollIntoView();
                    setTimeout(function(){  window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'memberErrorMessage');
            }
        },error:function(obj,status,error){
            $("#memberErrorMessage").html('Error in processing request').show();
        }
    });
}