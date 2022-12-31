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

function getPackageData(id,error_elem,state_sel,district_sel,ac_sel,pc_sel){
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
                        var range = package_data.receive_submission_range;//alert(range);
                        if(range == 'country'){
                            $("#country").val(1);
                            $("#country_div").show();
                        }else if(range == 'state'){
                            $("#country").val(1);
                            $("#country_div,#state_div").show();
                            getStatesListing('checkbox',error_elem,state_sel,'','');
                        }else if(range == 'district'){
                            $("#country").val(1);
                            getStatesListing('select',error_elem,state_sel,'district',district_sel);
                            if(district_sel != ''){
                                getDistrictListing(state_sel,'checkbox',error_elem,district_sel);
                            }
                            $("#country_div,#state_div,#district_div").show();
                        }else if(range == 'ac'){
                            $("#country").val(1);
                            getStatesListing('select',error_elem,state_sel,'ac',ac_sel);
                            if(ac_sel != ''){
                                getLACListing(state_sel,'checkbox',error_elem,ac_sel);
                            }
                            $("#country_div,#state_div,#ac_div").show();
                        }else if(range == 'pc'){
                            $("#country").val(1);
                            getStatesListing('select',error_elem,state_sel,'pc',pc_sel);
                            if(pc_sel != ''){
                                getPCListing(state_sel,'checkbox',error_elem,pc_sel);
                            }
                            $("#country_div,#state_div,#pc_div").show();
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

function getStatesListing(elem_type,error_elem,state_sel,target_elem,target_elem_sel){
    
    $.ajax({
        url:ROOT_PATH+"/state/listing/1",
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
                    var state_list = msg.state_list,chk = '',str = '',state_sel_arr = [],sel = '';
                    if(elem_type == 'checkbox'){
                        str = '<table><tr>';
                        if(state_sel != ''){
                            state_sel = state_sel.split(',');
                            for(var i=0;i<state_sel.length;i++){
                                state_sel_arr.push(parseInt(state_sel[i]));
                            }
                        }
                        for(var i=0;i<state_list.length;i++){
                            chk = (state_sel_arr.indexOf(state_list[i].id) >= 0)?'checked':''; 
                            str+='<td><input type="checkbox" name="state[]" id="state_'+state_list[i].id+'" '+chk+' value="'+state_list[i].id+'"> '+state_list[i].state_name+'</td>';
                            if(i > 0 && (i+1)%5 == 0) { str+='</tr><tr>'; } 
                        }

                        str+='</tr></table>';
                    }else{
                        str = '<select id="state" class="form-control" name="state"><option value="">State</option>';
                        for(var i=0;i<state_list.length;i++){
                            sel = (state_sel == state_list[i].id)?'selected':'';
                            str+='<option '+sel+' value="'+state_list[i].id+'">'+state_list[i].state_name+'</option>';
                        }
                        str+='</select>';
                    }
                    
                    $("#state_elem_div").html(str);
                    
                    if(target_elem == 'district'){
                        $("#state").on('change',function(){
                            getDistrictListing(this.value,'checkbox',error_elem,target_elem_sel); 
                        });
                    }
                    
                    if(target_elem == 'ac'){
                        $("#state").on('change',function(){
                            getLACListing(this.value,'checkbox',error_elem,target_elem_sel); 
                        });
                    }
                    
                    if(target_elem == 'pc'){
                        $("#state").on('change',function(){
                            getPCListing(this.value,'checkbox',error_elem,target_elem_sel); 
                        });
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


function getDistrictListing(state_id,elem_type,error_elem,district_sel){
    
    if(state_id == ''){ 
        $("#district_elem_div").html('');
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
                    var district_list = msg.district_list,chk = '',str = '',district_sel_arr = [],sel = '';
                    if(elem_type == 'checkbox'){
                        str = '<table><tr>';
                        if(district_sel != ''){
                            district_sel = district_sel.split(',');
                            for(var i=0;i<district_sel.length;i++){
                                district_sel_arr.push(parseInt(district_sel[i]));
                            }
                        }
                        for(var i=0;i<district_list.length;i++){
                            chk = (district_sel_arr.indexOf(district_list[i].id) >= 0)?'checked':''; 
                            str+='<td><input type="checkbox" name="district[]" id="district_'+district_list[i].id+'" '+chk+' value="'+district_list[i].id+'"> '+district_list[i].district_name+'</td>';
                            if(i > 0 && (i+1)%5 == 0) { str+='</tr><tr>'; } 
                        }

                        str+='</tr></table>';
                    }else{
                        str = '<select id="district" class="form-control" name="district"><option value="">District</option>';
                        for(var i=0;i<district_list.length;i++){
                            sel = (district_sel == district_list[i].id)?'selected':'';
                            str+='<option '+sel+' value="'+district_list[i].id+'">'+district_list[i].district_name+'</option>';
                        }
                        str+='</select>';
                    }

                    $("#district_elem_div").html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function getLACListing(state_id,elem_type,error_elem,lac_sel){
    
    if(state_id == ''){ 
        $("#ac_elem_div").html('');
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/lac-1/listing/"+state_id,
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
                    var lac_list = msg.lac_list,chk = '',str = '',lac_sel_arr = [],sel = '';
                    if(elem_type == 'checkbox'){
                        str = '<table><tr>';
                        if(lac_sel != ''){
                            lac_sel = lac_sel.split(',');
                            for(var i=0;i<lac_sel.length;i++){
                                lac_sel_arr.push(parseInt(lac_sel[i]));
                            }
                        }
                        for(var i=0;i<lac_list.length;i++){
                            chk = (lac_sel_arr.indexOf(lac_list[i].id) >= 0)?'checked':''; 
                            str+='<td><input type="checkbox" name="ac[]" id="ac_'+lac_list[i].id+'" '+chk+' value="'+lac_list[i].id+'"> '+lac_list[i].constituency_name+'</td>';
                            if(i > 0 && (i+1)%5 == 0) { str+='</tr><tr>'; } 
                        }

                        str+='</tr></table>';
                    }else{
                        str = '<select id="ac" class="form-control" name="ac"><option value="">Assembly Constituency</option>';
                        for(var i=0;i<lac_list.length;i++){
                            sel = (lac_sel == lac_list[i].id)?'selected':'';
                            str+='<option '+sel+' value="'+lac_list[i].id+'">'+lac_list[i].district_name+'</option>';
                        }
                        str+='</select>';
                    }

                    $("#ac_elem_div").html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}

function getPCListing(state_id,elem_type,error_elem,pc_sel){
    
    if(state_id == ''){ 
        $("#pc_elem_div").html('');
        return false;
    }
    
    $.ajax({
        url:ROOT_PATH+"/pc-1/listing/"+state_id,
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
                    var pc_list = msg.pc_list,chk = '',str = '',pc_sel_arr = [],sel = '';
                    if(elem_type == 'checkbox'){
                        str = '<table><tr>';
                        if(pc_sel != ''){
                            pc_sel = pc_sel.split(',');
                            for(var i=0;i<pc_sel.length;i++){
                                pc_sel_arr.push(parseInt(pc_sel[i]));
                            }
                        }
                        for(var i=0;i<pc_list.length;i++){
                            chk = (pc_sel_arr.indexOf(pc_list[i].id) >= 0)?'checked':''; 
                            str+='<td><input type="checkbox" name="pc[]" id="pc_'+pc_list[i].id+'" '+chk+' value="'+pc_list[i].id+'"> '+pc_list[i].constituency_name+'</td>';
                            if(i > 0 && (i+1)%5 == 0) { str+='</tr><tr>'; } 
                        }

                        str+='</tr></table>';
                    }else{
                        str = '<select id="pc" class="form-control" name="pc"><option value="">Assembly Constituency</option>';
                        for(var i=0;i<pc_list.length;i++){
                            sel = (pc_sel == pc_list[i].id)?'selected':'';
                            str+='<option '+sel+' value="'+pc_list[i].id+'">'+pc_list[i].district_name+'</option>';
                        }
                        str+='</select>';
                    }

                    $("#pc_elem_div").html(str);
                }
            }else{
                displayResponseError(msg,error_elem);
            }
        },error:function(obj,status,error){
            $("#"+error_elem).html('Error in processing request').show();
        }
    });
}