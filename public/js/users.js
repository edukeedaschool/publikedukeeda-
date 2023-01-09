"use strict";

function displayUserQualificationData(val){
    $(".qual-div").hide();
    
    if(val == 'pursing_graduate'){
        $("#qual-div-1").show();
        $("#qual-label-1").html('Expected Year of Degree Completion');
    }
    
    if(val == 'graduate' || val == 'post_graduate' || val == 'doctorate'){
        $("#qual-div-1").show();
        $("#qual-label-1").html('Passout Year');
    }
}

function submitAddUser(){
    $("#addUserFrm").submit();
}

$("#addUserFrm").on('submit', function(event){
    event.preventDefault(); 
    
    var formData = new FormData(this);
    
    $("#user_add_spinner").show();
    $("#user_add_submit,#user_add_cancel").attr('disabled',true);
    $(".invalid-feedback,#addUserErrorMessage,#addUserSuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/user/add",
        success:function(msg){
            $("#user_add_spinner").hide();
            $("#user_add_submit,#user_add_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#addUserErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#addUserSuccessMessage").html(msg.message).show();
                $("#addUserErrorMessage,.invalid-feedback").html('').hide();
                document.getElementById("addUserSuccessMessage").scrollIntoView();
                var url = ROOT_PATH+"/user/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#addUserErrorMessage").html('Error in processing request').show();
            $("#user_add_spinner").hide();
            $("#user_add_submit,#user_add_cancel").attr('disabled',false);
        }
    });
});

function submitEditUser(){
    $("#editUserFrm").submit();
}

$("#editUserFrm").on('submit', function(event){
    event.preventDefault(); 
    
    var formData = new FormData(this);
    var user_id = $("#user_id").val();
    
    $("#user_edit_spinner").show();
    $("#user_edit_submit,#user_edit_cancel").attr('disabled',true);
    $(".invalid-feedback,#editUserErrorMessage,#editUserSuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/user/edit/"+user_id,
        success:function(msg){
            $("#user_edit_spinner").hide();
            $("#user_edit_submit,#user_edit_cancel").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#editUserErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#editUserSuccessMessage").html(msg.message).show();
                $("#editUserErrorMessage,.invalid-feedback").html('').hide();
                document.getElementById("editUserSuccessMessage").scrollIntoView();
                var url = ROOT_PATH+"/user/list";
                setTimeout(function(){  window.location.href = url; }, 1000);
            }
        },error:function(obj,status,error){
            $("#editUserErrorMessage").html('Error in processing request').show();
            $("#user_edit_spinner").hide();
            $("#user_edit_submit,#user_edit_cancel").attr('disabled',false);
        }
    });
});

function updateUsers(action){
    var chk_vals = [];
    
    $(".user-id-chk").each(function (){
        if($(this).is(":checked")){
            chk_vals.push($(this).val());
        }
    });
    
    if(chk_vals.length == 0){
        alert('Please select Users');
        return false;
    }
    
    chk_vals = chk_vals.join(',');
    
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/user/update",
        method:"POST",
        data:{ids:chk_vals,action:action},
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#userErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#userSuccessMessage").html(msg.message).show();
                    $("#userErrorMessage,.invalid-feedback").html('').hide();
                    document.getElementById("userSuccessMessage").scrollIntoView();
                    setTimeout(function(){  window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'userErrorMessage');
            }
        },error:function(obj,status,error){
            $("#userErrorMessage").html('Error in processing request').show();
        }
    });
}

function followUnfollowUser(elem,user_id){
    $("#followErrorMessage,#followSuccessMessage,.invalid-feedback").html('').hide();
    
    var url = $(elem).hasClass('following')?'/user/unfollow':'/user/follow';
    ajaxSetup();//alert(url);

    $.ajax({
        url:ROOT_PATH+url,
        method:"POST",
        data:{user_id:user_id},
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','');//alert(errors);
                    if(errors != ''){
                        $("#followErrorMessage").html(errors).show();
                    } 
                }else{ 
                    //$("#followSuccessMessage").html(msg.message).show();
                    $("#followErrorMessage,.invalid-feedback").html('').hide();
                    if($(elem).hasClass('following')){
                        $(elem).removeClass('following').html('Follow');
                    }else{
                        $(elem).addClass('following').html('Unfollow');
                    }
                }
            }else{
                displayResponseError(msg,'followErrorMessage');
            }
        },error:function(obj,status,error){
            $("#followErrorMessage").html('Error in processing request').show();
        }
    });
}