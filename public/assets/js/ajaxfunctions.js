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
});

