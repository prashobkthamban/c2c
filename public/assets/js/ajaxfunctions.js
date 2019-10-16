function loadForm( id,viewfile ) {
    console.log('sap');
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

function didList(groupid, did_id) {
    //var groupid = $(this).val();
    $.ajax({
    type: "GET",
    url: '/get_did/'+ groupid, // This is the url we gave in the route
    success: function(res){ // What to do if we succeed
        //console.log(res);
      $('#did').find('option').not(':first').remove();
        $.each(res, function (i, item) {
            // $('#did').append($('<option>', { 
            //     value: i,
            //     text : item 
            // }));
            if(did_id == i) {
                $('#did').append('<option value="'+ i +'" selected>'+ item +'</option>');
            } else {
               $('#did').append('<option value="'+ i +'">'+ item +'</option>'); 
            }
            
        });
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
    }
  });
}

$(document).ready(function(){
    
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
        e.preventDefault();
        var cdrid = $('input[name="cdrid"]').val();
        var noteHTML = "";
        console.log($('#'+this.id).serialize());
        var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addNote") }}', // This is the url we gave in the route
            data: $('#'+this.id).serialize(),
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
                    //console.log(moment(new Date()).format('YYYY-MM-DD hh:mm:ss'));
                    noteHTML += "<tr>";
                    noteHTML += "<td>" + res.result.operator  + "</td>";
                    noteHTML += "<td>" + moment(res.result.datetime.date).format('YYYY-MM-DD hh:mm:ss')  + "</td>";
                    noteHTML += "<td>" + res.result.note  + "</td>";
                    noteHTML += "<td><a href='#' class='text-danger mr-2'><i class='nav-icon i-Close-Window font-weight-bold'></td>";
                    noteHTML += "</tr>";
                    $("#comments_table tbody").append(noteHTML);
                    $("#notes_"+cdrid).removeClass('d-none');
                    $("#add_note_"+cdrid).addClass('d-none');
                    toastr.success(res.success);                
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
        var cdrid = this.id;
        var callerid = cdrid.replace("add_contact", "");
        var errors = ''; 

        $.ajax({
        type: "POST",
        url: '{{ URL::route("addContact") }}', // This is the url we gave in the route
        data: $('#'+this.id).serialize(),
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
                $("#callerid_"+callerid).html("<i class='fa fa-phone'></i>"+res.fname);
                xajax_hide();
                toastr.success(res.success);                
            }
           
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            toastr.error('Some errors are occured');
        }
        });
        });

    //add and update tag
    $( '.tag_form' ).on( 'submit', function(e) {
        e.preventDefault();
        var cdrid = this.id;
        var tagid = cdrid.replace("tag_form", "");
        var errors = ''; 

        $.ajax({
        type: "POST",
        url: '{{ URL::route("addTag") }}', // This is the url we gave in the route
        data: $('#'+this.id).serialize(),
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
                    $("#tag_"+tagid).text(res.tag);
                    xajax_hide();
                    toastr.success(res.success);                
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
        });
    });

    //delete comment
    $(".delete_comment").click(function() { 
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
                        value: i,
                        text : item 
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

