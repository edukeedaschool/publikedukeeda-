"use strict";

var INV_TYPE_ARNON = 'Arnon';
var INV_TYPE_NORTH = 'NorthCorp';

$(document).ready(function(){
    if($('.static-header-tbl').length > 0){
        $('.static-header-tbl').stickyTableHeaders();
        /*$('.static-header-tbl tr').bind('click',function(){
           if($(this).hasClass('report-sel-tr'))  $(this).removeClass('report-sel-tr');else $(this).addClass('report-sel-tr');
        });*/
    }
})

function getResponseErrors(obj,separator_tag,prefix_elem){
    var errors = '';
    if(typeof obj.errors !== 'undefined'){
        if(typeof obj.errors  === 'string'){
            errors = obj.errors;
        }else{
            if(prefix_elem != ''){
                $.each( obj.errors, function( key, value) {
                    $("#"+prefix_elem+key).html(value).show();
                });
            }else{
                $.each( obj.errors, function( key, value) {
                    errors+=value+separator_tag;
                });
            }
        }
    }else{
        if(typeof obj.message !== 'undefined'){
            errors = obj.message;  
        }
    }
    
    return errors;
}

function ajaxSetup(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

function checkAllCheckboxes(elem,type){
    if($(elem).is(':checked')){
        $("."+type+"-chk").prop("checked", true);
    }else{
        $("."+type+"-chk").prop("checked", false);
    }
}

function switchRoleHeader(role_id){
    $("#switch_role_id_header").val(role_id);
    $("#switchRoleFrmHeader").submit();
}

function sendNotification(type_id,ref_id){
    ajaxSetup();
    $.ajax({
        type: "POST",
        data:{type_id:type_id,ref_id:ref_id},
        url:ROOT_PATH+"/user/sendnotification",
        success: function(msg){		
            if(msg.status == 'fail'){
                var errors = getResponseErrors(msg,'<br/>','');
                if(errors != ''){
                    $("#errortatusMessage").html(errors).show();
                } 
            }else{ 
                displayStatusText(msg.message,'success');
                setTimeout(function(){  window.location.reload(); }, 2000);
            }
        },
        error:function(obj,status,error){
            $("#errortatusMessage").html('Error in processing request').show();
        }
    });
}



function validateFileExt(filevalue) { 
    var filePath = filevalue;//fileInput.value; 

    // Allowing file type 
    var allowedExtensions =  /(\.jpg|\.jpeg|\.png|\.gif|\.bmp)$/i; 

    if (!allowedExtensions.exec(filePath)) { 
        return false; 
    }else{
        return true;
    }
} 

function displayDialogImage(img_url){
    $("#image_common_dialog_content").html('<img src="'+img_url+'" class="design-item-image">');
    $("#image_common_dialog").modal('show');
}

function displayResponseError(msg,error_elem_id){
    msg = msg+"";
    if(msg.indexOf('access_denied_page') !== false){
        $("#"+error_elem_id).html('Access Denied').show();
    }else{
        $("#"+error_elem_id).html('Error in processing request').show();
    }
}

function objectPropertyExists(msg,property){
    return msg.hasOwnProperty(property);
}

function loadColorDropdown(){
    for(const dropdown of document.querySelectorAll(".custom-select-wrapper")) {
        dropdown.addEventListener('click', function() {
            this.querySelector('.custom-select').classList.toggle('open');
        })
    }

    window.addEventListener('click', function(e) {
        for (const select of document.querySelectorAll('.custom-select')) {
            if (!select.contains(e.target)) {
                select.classList.remove('open');
            }
        }
    });

    $(".span-custom-option").bind('click',function(){
        var id = $(this).attr('data-value');
        $(this).parents('.custom-select').find('.custom-select__trigger span').html($(this).html());
        $(this).parents('.custom-select-wrapper').find(".color-hdn").val(id);
        $(this).parents('.custom-select-wrapper').find(".color-name-hdn").val($(this).html());
    });

    $(".span-custom-option").bind('mouseover',function(){
        var val = $(this).attr('data-value');
        $(this).parents('.custom-options').find('.span-text-'+val).css('background-color','#C7DFF9');
    });
    $(".span-custom-option").bind('mouseout',function(){
        var val = $(this).attr('data-value');
        $(this).parents('.custom-options').find('.span-text-'+val).css('background-color','#ffffff');
    });
}

function validateNumericField(elem){
    if(isNaN($(elem).val())){
        $(elem).val('');
    }
}



function formatDate(d){
    d = new Date(d); 
    var datestring = ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" +
    d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);

    return datestring;
}

function displayDate(d){
    d = new Date(d); 
    var datestring = ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" +d.getFullYear();

    return datestring;
}

function getCurrentDate(type){
    var currentdate = new Date(); 
    
    if(type == 1){
        var datetime = currentdate.getDate()+"/"+(currentdate.getMonth()+1)+"/"+currentdate.getFullYear()+" "  
        +currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds();
    }else if(type == 2){
        var datetime = currentdate.getFullYear()+"/"+(currentdate.getMonth()+1)+"/"+currentdate.getDate()+" "  
        +currentdate.getHours()+":"+currentdate.getMinutes()+":"+currentdate.getSeconds();
    }
        
    return datetime;
}

function getAjaxPagingLinks(product_list){
    var paging_links = '';
    if(product_list.total > 0){
        paging_links+=' <div class="separator-10"></div> Displaying Page '+product_list.current_page+" of total "+product_list.last_page+" Pages";
        paging_links+=' | Displaying Records '+product_list.from+" to "+product_list.to+" of total "+product_list.total+" Records";
    }
    
    return paging_links;
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}



