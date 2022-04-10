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
    $('.phone_number').on('keypress', function(){
        var maxLength = $(this).val().length;
        if (maxLength >= 10) {
            return false;
        }
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
                    /*noteHTML += "<tr class='cmnt_row_" + res.id + "'>";
                    noteHTML += "<td>" + res.result.operator  + "</td>";
                    noteHTML += "<td>" + moment(res.result.datetime.date).format('YYYY-MM-DD hh:mm:ss')  + "</td>";
                    noteHTML += "<td>" + res.result.note + "</td>";
                    noteHTML += "<td><a href='#' class='text-danger mr-2 delete_comment' id='" + res.id + "'><i class='nav-icon i-Close-Window font-weight-bold'></td>";
                    noteHTML += "</tr>";
                    $("#notes_list_table tbody").append(noteHTML);*/
                    $("#add_note_modal").modal('hide');
                    toastr.success(res.success);        
                    setTimeout( function() { 
                            location.reload(true); 
                    }, 300);             
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
                setTimeout( function() { 
                        location.reload(true); 
                }, 800);            
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
                    $('#row_' + cdrid + ' .more-details').attr('data-tag', res.tag);
                    $('#row_' + cdrid + ' .edit_tag').attr('data-tag', res.tag);
                    $('.tag_btn_' + cdrid).attr('data-tag', res.tag);
                    $("#cdrTag_"+cdrid).text(res.tag);
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