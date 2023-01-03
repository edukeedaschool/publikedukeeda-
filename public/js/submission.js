"use strict";

function getSubmissionSubscribersList(sub_group_id,user_id,select_elem,error_elem,sel_val){
    var str = '<option value="">Subscriber</option>';
    
    if(sub_group_id == '' || user_id == '') {
        $("#"+select_elem).html(str);
        return false
    };
        
    $.ajax({
        url:ROOT_PATH+"/submission-subscribers/list/"+sub_group_id+"/"+user_id,
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
                    var subscriber_list = msg.subscriber_list,sel = '';//alert(user_data);
                    if(subscriber_list != null && subscriber_list != ''){
                       for(var i=0;i<subscriber_list.length;i++){
                            sel = (sel_val == subscriber_list[i].id)?'selected':'';
                            str+='<option '+sel+' value="'+subscriber_list[i].id+'">'+subscriber_list[i].subscriber_name+'</option>';
                        }

                        $("#"+select_elem).html(str);
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

function submitAddSubmissionSubscriber(){
    $(".invalid-feedback").hide();
    var validation = true;
    
    if($("#sub_group_id").val() == ''){
        $("#error_validation_sub_group_id").html('Sub Group is Required Field').show();
        validation = false;
    }

    if($("#subscriber_id").val() == ''){
        $("#error_validation_subscriber_id").html('Subscriber is Required Field').show();
        validation = false;
    }

    if(validation === false){
        return false;
    }
    
    var subscriber_id = $("#subscriber_id").val();
    
    var url = ROOT_PATH+"/submission/type/add/"+subscriber_id;
    setTimeout(function(){  window.location.href = url; }, 800);
}

function selectSubmissionType(id){//alert(id);
    $(".btn-sub-type").removeClass('btn-selected');
    $("#link_"+id).addClass('btn-selected');
    $("#submission_type_id").val(id);
    $("#addsubmissionErrorMessage").html('').hide();
}

function submitAddSubmissionType(){
    if($("#submission_type_id").val() == ''){
        $("#addsubmissionErrorMessage").html('Submission Type is Required Field').show();
        return false;
    }
    
    var subscriber_id = $("#subscriber_id").val();
    var submission_type_id = $("#submission_type_id").val();
    
    var url = ROOT_PATH+"/submission/detail/add/"+subscriber_id+"/"+submission_type_id;
    window.location.href = url;
}

function submitAddSubmissionDetail(){
    $("#saveSubmissionDetailForm").submit();
}

$("#saveSubmissionDetailForm").on('submit', function(event){
    event.preventDefault(); 
    var formData = new FormData(this);
    
    $("#submission_detail_btn").attr('disabled',true);
    $(".invalid-feedback,#addsubmissionSuccessMessage,#addsubmissionErrorMessage").html('').hide();
    
    ajaxSetup();		
    
    $.ajax({
        type: "POST",
        method:"POST",
        data:formData,
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        url:ROOT_PATH+"/submission/detail/save",
        success:function(msg){
            $("#submission_detail_btn").attr('disabled',false);
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','error_validation_');
                
                if(errors != ''){
                    $("#addsubmissionErrorMessage").html(errors).show();
                } 
            }else{ 
                $("#addsubmissionSuccessMessage").html(msg.message).show();
                $("#addsubmissionErrorMessage,.invalid-feedback").html('').hide();
                var submission_data = msg.submission_data
                
                var url = ROOT_PATH+"/submission/confirm/"+submission_data.id;
                setTimeout(function(){  window.location.href = url; }, 800);
            }
        },error:function(obj,status,error){
            $("#addsubmissionErrorMessage").html('Error in processing request').show();
            $("#submission_detail_btn").attr('disabled',false);
        }
    });
});
