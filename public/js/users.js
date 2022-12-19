"use strict";

function deleteUser(id){
    $("#delete_user_dialog").modal('show');
    $("#user_delete_id").val(id);
    $("#deleteUserErrorMessage,#deleteUserSuccessMessage,.invalid-feedback").html('').hide();
}

function deleteUser2(){
   var form_data = $("#deleteUserFrm").serialize();
    $("#user_delete_spinner").show();
    $("#deleteUserErrorMessage,#deleteUserSuccessMessage,.invalid-feedback").html('').hide();
    $("#user_delete_submit,#user_delete_cancel").attr('disabled',true);
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/user/delete",
        method:"POST",
        data:form_data,
        success:function(msg){
            $("#user_delete_submit,#user_delete_cancel").attr('disabled',false);
            if(objectPropertyExists(msg,'status')){
                $("#user_delete_spinner").hide();

                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#deleteUserErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#deleteUserSuccessMessage").html(msg.message).show();
                    $("#deleteUserErrorMessage,.invalid-feedback").html('').hide();
                    setTimeout(function(){  $("#delete_user_dialog").modal('hide');window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'deleteUserErrorMessage');
            }
        },error:function(obj,status,error){
            $("#deleteUserErrorMessage").html('Error in processing request').show();
            $("#user_delete_spinner").hide();
            $("#user_delete_submit,#user_delete_cancel").attr('disabled',false);
        }
    });
}

function editUser(id){
    $("#edit_user_dialog").modal('show');
    $("#user_edit_id").val(id);
    $("#editUserErrorMessage,#editUserSuccessMessage,.invalid-feedback").html('').hide();
     
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/user/data/"+id,
        method:"GET",
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','');
                    if(errors != ''){
                        $("#editUserErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#userName").val(msg.user_data.name);
                    $("#userEmail").val(msg.user_data.email);
                    $("#defaultUserType").val(msg.user_data.user_role);
                    $("#userTypes").val(msg.user_roles);
                }
            }else{
                displayResponseError(msg,'editUserErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editUserErrorMessage").html('Error in processing request').show();
        }
    });
}

function updateUser(){
    var form_data = $("#editUserFrm").serialize();
    $("#user_edit_spinner").show();
    $("#editUserErrorMessage,#editUserSuccessMessage,.invalid-feedback").html('').hide();
    $("#user_edit_submit,#user_edit_cancel").attr('disabled',true);
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/user/update",
        method:"POST",
        data:form_data,
        success:function(msg){
            $("#user_edit_submit,#user_edit_cancel").attr('disabled',false);
            if(objectPropertyExists(msg,'status')){
                $("#user_edit_spinner").hide();

                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editUserErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editUserSuccessMessage").html(msg.message).show();
                    $("#editUserErrorMessage,.invalid-feedback").html('').hide();
                    setTimeout(function(){  $("#edit_user_dialog").modal('hide');window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'editUserErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editUserErrorMessage").html('Error in processing request').show();
            $("#user_edit_spinner").hide();
            $("#user_edit_submit,#user_edit_cancel").attr('disabled',false);
        }
    });
}

function addUser(){
    $("#addUserSuccessMessage,#addUserErrorMessage,.invalid-feedback").html('').hide();
    $("#addUserFrm .form-control").val('');
    $("#add_user_dialog").modal('show');
}

function submitAddUser(){
    var form_data = $("#addUserFrm").serialize();
    $("#user_add_spinner").show();
    $("#addUserSuccessMessage,#addUserErrorMessage,.invalid-feedback").html('').hide();
    $("#user_add_submit,#user_add_cancel").attr('disabled',true);
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/user/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            $("#user_add_submit,#user_add_cancel").attr('disabled',false);
            $("#user_add_spinner").hide();
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addUserErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addUserSuccessMessage").html(msg.message).show();
                    $("#addUserErrorMessage,.invalid-feedback").html('').hide();
                    setTimeout(function(){  $("#add_user_dialog").modal('hide'); window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'addUserErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addUserErrorMessage").html('Error in processing request').show();
            $("#user_add_spinner").hide();
            $("#user_add_submit,#user_add_cancel").attr('disabled',false);
        }
    });
}


function submitUpdateUserLinks(){
    var form_data = $("#updateUserLinksFrm").serialize();
    $("#user_add_spinner").show();
    $("#updateUserLinksErrorMessage,#updateUserLinksSuccessMessage,.invalid-feedback").html('').hide();
    $("#link_update_cancel,#link_update_submit").attr('disabled',true);
    var user_id = $("#user_id").val();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/user/link/edit/"+user_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            $("#link_update_submit,#link_update_cancel").attr('disabled',false);
            $("#user_add_spinner").hide();
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#updateUserLinksErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#updateUserLinksSuccessMessage").html(msg.message).show();
                    $("#updateUserLinksErrorMessage,.invalid-feedback").html('').hide();
                    setTimeout(function(){  window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'updateUserLinksErrorMessage');
            }
        },error:function(obj,status,error){
            $("#updateUserLinksErrorMessage").html('Error in processing request').show();
            $("#user_add_spinner").hide();
            $("#link_update_submit,#link_update_cancel").attr('disabled',false);
        }
    });
}

function submitEditUserProfile(){
    var form_data = $("#editProfileFrm").serialize();
    $("#profile_edit_spinner").show();
    $("#editProfileSuccessMessage,#editProfileErrorMessage,.invalid-feedback").html('').hide();
    $("#profile_edit_cancel,#profile_edit_submit").attr('disabled',true);
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/account/profile/edit",
        method:"POST",
        data:form_data,
        success:function(msg){
            $("#profile_edit_submit,#profile_edit_cancel").attr('disabled',false);
            $("#profile_edit_spinner").hide();
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editProfileErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editProfileSuccessMessage").html(msg.message).show();
                    $("#editProfileErrorMessage,.invalid-feedback").html('').hide();
                    setTimeout(function(){  window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'editProfileErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editProfileErrorMessage").html('Error in processing request').show();
            $("#profile_edit_spinner").hide();
            $("#profile_edit_cancel,#profile_edit_submit").attr('disabled',false);
        }
    });
}