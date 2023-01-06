"use strict";

function displayQualificationData(val){
    $(".qual-div").hide();
    
    if(val == 'pursing_graduate'){
        $(".qual-div").css('display','flex');
        $("#qual-label-1").html('Expected Year of Degree Completion');
    }
    
    if(val == 'graduate' || val == 'post_graduate' || val == 'doctorate'){
        $(".qual-div").css('display','flex');
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


function getDistrictList(state_id,select_elem,error_elem,sel_val){
    var str = '<option value="">District</option>';
    
    if(state_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/api-data?action=district_list&state_id="+state_id,
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

function getSubDistrictList(district_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Sub District</option>';
    
    if(district_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/api-data?action=sub_district_list&district_id="+district_id,
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

function getVillageList(sub_district_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Village</option>';
    
    if(sub_district_id == ''){ 
        $("#"+select_elem).html(str);
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/api-data?action=village_list&sub_district_id="+sub_district_id,
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

function editUserProfile(profile_type,action){
    if(action == 'edit'){
        $("#"+profile_type+"_div .form-control").attr('readonly',false).css('background-color','#fff');
        $("."+profile_type+"-edit").hide();
        $("."+profile_type+"-save").show();
        $("."+profile_type+"-cancel").show();
    }
    
    if(action == 'cancel'){
        $("#"+profile_type+"_div .form-control").attr('readonly',true).css('background-color','#e9ecef');
        $("."+profile_type+"-edit").show();
        $("."+profile_type+"-save").hide();
        $("."+profile_type+"-cancel").hide();
    }
    
    if(action == 'save'){
        $("#profileUpdateSuccessMessage,#profileUpdateErrorMessage,.invalid-feedback").html('').hide();
        
        var form_data = $("#"+profile_type+"_form").serialize(); 
        ajaxSetup();

        $.ajax({
            url:ROOT_PATH+"/api-data?action=update_profile&profile_type="+profile_type,
            method:"POST",
            data:form_data,
            success:function(msg){
                if(objectPropertyExists(msg,'status')){
                    if(msg.status == 'fail'){
                        var errors = getResponseErrors(msg,'<br/>','error_validation_');
                        if(errors != ''){
                            $("#profileUpdateErrorMessage").html(errors).show();
                        } 
                    }else{ 
                        $("#profileUpdateSuccessMessage").html(msg.message).show();
                        $("#profileUpdateErrorMessage,.invalid-feedback").html('').hide();
                        document.getElementById("profileUpdateSuccessMessage").scrollIntoView();
                        $("#"+profile_type+"_div .form-control").attr('readonly',true).css('background-color','#e9ecef');;
                        $("."+profile_type+"-edit").show();
                        $("."+profile_type+"-save").hide();
                        $("."+profile_type+"-cancel").hide();
                    }
                }else{
                    displayResponseError(msg,'profileUpdateErrorMessage');
                }
            },error:function(obj,status,error){
                $("#profileUpdateErrorMessage").html('Error in processing request').show();
            }
        });
    }
}

function updateUserProfileImage(){
    $("#profile_image_form").submit();
}

$("#profile_image_form").on('submit', function(event){
    event.preventDefault(); 
    var formData = new FormData(this);
    
    $(".invalid-feedback,#profileUpdateErrorMessage,#profileUpdateSuccessMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/api-data?action=update_profile_image&profile_type=profile_image",
        success:function(msg){
            
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#profileUpdateErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#profileUpdateSuccessMessage").html(msg.message).show();
                $("#profileUpdateErrorMessage,.invalid-feedback").html('').hide();
                
                setTimeout(function(){  window.location.reload(); }, 1000);
            }
        },error:function(obj,status,error){
            $("#profileUpdateErrorMessage").html('Error in processing request').show();
            
        }
    });
});

function editProfileImage(action){
    if(action == 'edit'){
        $("#edit_image_div").show();
        $("#profile_image").val('').css('background-color','#fff');
        $(".image-edit").hide();
        $(".image-save,.image-cancel").show();
    }
    
    if(action == 'cancel'){
        $("#edit_image_div").hide();
        $(".image-edit").show();
        $(".image-save,.image-cancel").hide();
        $("#profile_image").val('').css('background-color','#e9ecef');
    }
}