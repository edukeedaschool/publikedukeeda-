"use strict";

function submitAddPackage(){
    $("#addPackageErrorMessage,#addPackageSuccessMessage,.invalid-feedback").html('').hide();
    var form_data = $("#addPackageFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/package/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addPackageErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addPackageSuccessMessage").html(msg.message).show();
                    $("#addPackageErrorMessage,.invalid-feedback").html('').hide();
                    
                    var url = ROOT_PATH+"/package/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addPackageErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addPackageErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditPackage(){
    $("#editPackageErrorMessage,#editPackageSuccessMessage,.invalid-feedback").html('').hide();
    var package_id = $("#package_id").val();
    var form_data = $("#editPackageFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/package/edit/"+package_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editPackageErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editPackageSuccessMessage").html(msg.message).show();
                    $("#editPackageErrorMessage,.invalid-feedback").html('').hide();
                    var url = ROOT_PATH+"/package/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editPackageErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editPackageErrorMessage").html('Error in processing request').show();
        }
    });
}

function updatePackages(action){
    var chk_vals = [];
    
    $(".package-id-chk").each(function (){
        if($(this).is(":checked")){
            chk_vals.push($(this).val());
        }
    });
    
    if(chk_vals.length == 0){
        alert('Please select Package');
        return false;
    }
    
    chk_vals = chk_vals.join(',');
    
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/package/update",
        method:"POST",
        data:{ids:chk_vals,action:action},
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#packageErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#packageSuccessMessage").html(msg.message).show();
                    $("#packageErrorMessage,.invalid-feedback").html('').hide();
                    document.getElementById("packageSuccessMessage").scrollIntoView();
                    setTimeout(function(){  window.location.reload(); }, 1000);
                }
            }else{
                displayResponseError(msg,'packageErrorMessage');
            }
        },error:function(obj,status,error){
            $("#packageErrorMessage").html('Error in processing request').show();
        }
    });
}

