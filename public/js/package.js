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


function submitEditPackagePrice(){
    $("#editPackagePriceErrorMessage,#editPackagePriceSuccessMessage,.invalid-feedback").html('').hide();
    //var package_id = $("#package_id").val();
    var form_data = $("#editPackagePriceFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/package-price/edit",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editPackagePriceErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editPackagePriceSuccessMessage").html(msg.message).show();
                    $("#editPackagePriceErrorMessage,.invalid-feedback").html('').hide();
                    document.getElementById("editPackagePriceSuccessMessage").scrollIntoView();
                    var url = ROOT_PATH+"/package-price/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editPackagePriceErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editPackagePriceErrorMessage").html('Error in processing request').show();
        }
    });
}

function getPackageData(id,error_elem){
    $("#district,#pc,#ac").html('<option value="">Select One</option>').val('');
    $("#country,#state").val('');
    $("#country_div,#state_div,#district_div,#pc_div,#ac_div").hide();
    
    if(id == ''){ 
       return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/package/data/"+id,
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
                    var package_data = msg.package_data;
                    if(package_data.receive_submission == 'yes'){
                        var range = package_data.receive_submission_range;
                        if(range == 'country'){
                            $("#country_div").show();
                        }else if(range == 'state'){
                            $("#country_div,#state_div").show();
                        }else if(range == 'district'){
                            $("#country_div,#state_div,#district_div").show();
                        }else if(range == 'ac'){
                            $("#country_div,#state_div,#district_div,#ac_div").show();
                        }else if(range == 'pc'){
                            $("#country_div,#state_div,#district_div,#pc_div").show();
                        }
                    }
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function submitAddSubscriberPackage(){
    $("#addSubscriberPackageErrorMessage,#addSubscriberPackageSuccessMessage,.invalid-feedback").html('').hide();
    
    var form_data = $("#addSubscriberPackageFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/subscriber-package/add",
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#addSubscriberPackageErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#addSubscriberPackageSuccessMessage").html(msg.message).show();
                    $("#addSubscriberPackageErrorMessage,.invalid-feedback").html('').hide();
                    document.getElementById("addSubscriberPackageSuccessMessage").scrollIntoView();
                    var url = ROOT_PATH+"/subscriber-package/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'addSubscriberPackageErrorMessage');
            }
        },error:function(obj,status,error){
            $("#addSubscriberPackageErrorMessage").html('Error in processing request').show();
        }
    });
}

function submitEditSubscriberPackage(){
    $("#editSubscriberPackageErrorMessage,#editSubscriberPackageSuccessMessage,.invalid-feedback").html('').hide();
    var subscriber_package_id = $("#subscriber_package_id").val();
    var form_data = $("#editSubscriberPackageFrm").serialize();
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/subscriber-package/edit/"+subscriber_package_id,
        method:"POST",
        data:form_data,
        success:function(msg){
            if(objectPropertyExists(msg,'status')){
                if(msg.status == 'fail'){
                    var errors = getResponseErrors(msg,'<br/>','error_validation_');
                    if(errors != ''){
                        $("#editSubscriberPackageErrorMessage").html(errors).show();
                    } 
                }else{ 
                    $("#editSubscriberPackageSuccessMessage").html(msg.message).show();
                    $("#editSubscriberPackageErrorMessage,.invalid-feedback").html('').hide();
                    document.getElementById("editSubscriberPackageSuccessMessage").scrollIntoView();
                    var url = ROOT_PATH+"/subscriber-package/list";
                    setTimeout(function(){  window.location.href = url; }, 1000);
                }
            }else{
                displayResponseError(msg,'editSubscriberPackageErrorMessage');
            }
        },error:function(obj,status,error){
            $("#editSubscriberPackageErrorMessage").html('Error in processing request').show();
        }
    });
}

function updateSubscriberPackages(action){
    var chk_vals = [];
    
    $(".package-id-chk").each(function (){
        if($(this).is(":checked")){
            chk_vals.push($(this).val());
        }
    });
    
    if(chk_vals.length == 0){
        alert('Please select Subscriber Package');
        return false;
    }
    
    chk_vals = chk_vals.join(',');
    
    ajaxSetup();

    $.ajax({
        url:ROOT_PATH+"/subscriber-package/update",
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