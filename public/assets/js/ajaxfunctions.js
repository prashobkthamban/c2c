 function copyval(param, content){
     document.getElementById(content).value = document.getElementById(content).value + $("#"+param ).val() ;
 }
function loadForm( id,viewfile ) {
    $("#modal-wrapper").html('Please wait...');
    $.ajax({
        type: 'POST',
        //url: '{{URL::to('/getForm')}}',
        url: '/getForm',
        data: {
            'viewfile': viewfile,
            'id': id
        },
        success: function (data) {
            if (data.success == 1) {
                $("#model-wrapper").html(data.view);
            } else if (data.error == 1) {
                alert(data.errormsg);
            }
        }

    });
}

function deleteItem(id, table) {
    if (confirm('Are you sure want to delete this item ?')) {
        $.ajax({
            type: "DELETE",
            url: '/delete_item/'+ id +'/' + table, // This is the url we gave in the route
            success: function(result) { 
                if(result) {
                    $("#row_"+id).remove();
                    if ($("#search_btn").length) {
                        $("#search_btn").trigger('click');
                    } else {
                        reloadDataTable();
                    }
                    toastr.success('Delete item successfully.'); 
                } else {
                    toastr.error('Some errors are occured.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
        });
    }
}

function didList(groupid, did_id) {
    //var groupid = $(this).val();
    $.ajax({
    type: "GET",
    url: '/get_did/'+ groupid, // This is the url we gave in the route
    success: function(res){ // What to do if we succeed
      $('#did').find('option').not(':first').remove();
        $.each(res, function (i, item) {
            if(did_id == item.id) {
                $('#did').append('<option value="'+ item.id +'" selected>'+ item.did +'</option>');
            } else {
               $('#did').append('<option value="'+ item.id +'">'+ item.did +'</option>'); 
            }
            
        });
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
    }
  });
}

$(document).ready(function(){
    // phone number validation
    $('.phone_number').on('keyup', function() {
        let number = $(this).val().replace(/[^0-9+]/g, '');
        $(this).val(number);
    });
    $('.lead-category').change(function (){
        $.ajax({
            type: 'POST',
            //url: '{{URL::to('/getForm')}}',
            url: '/getSubCategory',
            data: {
                
                'id': $(this).val()
            },
            success: function (data) {
                if (data.success == 1) {
                    $('.lead-sub-category').empty();
                    $(".lead-sub-category").html(data.view);
                } else if (data.error == 1) {
                    alert(data.errormsg);
                }
            }
        });
    });

    //add note
    $( '.notes_form' ).on( 'submit', function(e) {
        let uniqueId = $(this).find('#uniqueid').val();
        e.preventDefault();
        var noteHTML = "";
        var errors = ''; 
          $.ajax({
            type: "POST",
            url: '/add_note', // This is the url we gave in the route
            data: $('.notes_form').serialize(),
            success: function(res){ // What to do if we succeed
                if(res.error) {
                    $.each(res.error, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            errors += value[0];
                            errors += "</br>";
                        }
                    });
                    toastr.error(errors);
                } else {
                    $("#add_note_modal").modal('hide');
                    toastr.success(res.success);
                    $('.notes_form')[0].reset();
                    if ($('#cdr_table').length) {
                        $("#notes_" + uniqueId.replace('.', '\\.')).removeClass('hidden');
                    } else {
                        setTimeout( function() {
                                location.reload(true);
                        }, 300);
                    }         
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
    });

    //add and update contact
    $( '.contact_form' ).on( 'submit', function(e) {
        e.preventDefault();
        var errors = ''; 

        $.ajax({
        type: "POST",
        url: '/add_cdr_contact', // This is the url we gave in the route
        data: $('.contact_form').serialize(),
        success: function(res){ // What to do if we succeed
            if(res.error) {
                $.each(res.error, function(index, value)
                {
                    if (value.length != 0)
                    {
                        errors += value[0];
                        errors += "</br>";
                    }
                });
                toastr.error(errors);
            } else {
                $("#contact_modal").modal('hide');
                toastr.success(res.success);
                let id = $("#contact_form_contact_id").val();
                if ($('#cdr_table').length) {
                    $('[title="' + res.phone + '"]').html('<i class="i-Telephone"></i>' + res.callerId);
                    $('.contact_' + id).attr('data-contact-id', 'contact_' + res.contactId);
                    $('.contact_' + id).attr('data-fname', res.fname);
                    $('.contact_' + id).attr('data-lname', res.lname);
                    $('.contact_' + id).attr('data-email', res.email);
                    $('.contact_' + id).attr('data-phone', res.phone);
                    $('.contact_' + id).text('Update Contact');
                } else {
                    setTimeout( function() { 
                            location.reload(true); 
                    }, 800); 
                }         
            }
           
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            toastr.error('Some errors are occured');
        }
        });
        });

    //add and update tag in cdr report
    $( '.tag_form' ).on( 'submit', function(e) {
        e.preventDefault();
        var cdrid = $("#cdrid").val();
        var errors = ''; 
        $.ajax({
        type: "POST",
        url: '/add_tag', // This is the url we gave in the route
        data: $('.tag_form').serialize(),
            success: function(res){ // What to do if we succeed
                if(res.error) {
                    $.each(res.error, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            errors += value[0];
                            errors += "</br>";
                        }
                    });
                    toastr.error(errors);
                } else {
                    $('#tag_span_' + cdrid).html(res.tag);
                    $('#row_' + cdrid + ' .edit_tag').attr('data-tag', res.tag);
                    $('.tag_btn_' + cdrid).attr('data-tag', res.tag);
                    $("#tag_"+cdrid).text('Update Tag');
                    $("#tag_modal").modal('hide');
                    toastr.success(res.success);                
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
        });
    });

    //delete comment
    $(document).on('click', '.delete_comment', function(){
        var id = this.id;
        if (confirm('Are you sure you want to delete this Comment?')) {
            $.ajax({
                url: "delete_comment/"+id,
                type: 'DELETE',
                success: function (res) {

                    if(res.status == 1) {

                        if (res.notesCount == 0) {
                            $("#notes_" + res.uniqueId.replace('.', '\\.')).addClass('hidden');
                        }
                        $(".cmnt_row_"+id).remove();
                        toastr.success('Comment delete successfully.')
                    }
                    
                }
            });
        } 
    });

    $('#groupid').on('change',function(e)
        {
            var groupid = $(this).val();
            $.ajax({
            type: "GET",
            url: '/get_did/'+ groupid, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                //console.log(res);
              $('#did').find('option').not(':first').remove();
                $.each(res, function (i, item) {
                    $('#did').append($('<option>', { 
                        value: item.id,
                        text : item.did 
                    }));
                });
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        }); 

    $('#resellerid').on('change',function(e)
    {
        var resellerid = $(this).val();
        $.ajax({
        type: "GET",
        url: '/get_customer/admin/'+ resellerid, // This is the url we gave in the route
        success: function(res){ // What to do if we succeed

            $('#groupid').find('option').not(':first').remove();
            $.each(res, function (i, item) {
                $('#groupid').append($('<option>', { 
                    value: i,
                    text : item 
                }));
            });
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        }
        });
    });
     
});

function loadModal(modalId, title, content) {
    if ($('#'+modalId+'Modal').length) {
        $('#'+modalId+'Modal .modal-title').text(title);
        $('#'+modalId+'Modal .modal-body').html(content);
        $('#'+modalId+'Modal').modal('show');
    } else {
        console.error(modalId+'Modal element not found!')
    }
}

function ajaxCall(url, data) {
    return $.ajax({
        type: 'POST',
        url: url,
        data: data,
        success: function(result) {},
        error: function(error) {
            //some toast message
            toastr.error('Sorry! We are facing some technical difficulties. Please try after sometime.');
        }
    });
}