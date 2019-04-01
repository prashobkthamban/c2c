function loadForm( id,viewfile ) {
    $("#modal-wrapper").html('Please wait...');
    $.ajax({
        type: 'POST',
        //url: '{{URL::to('/getForm')}}',
        url: '/getForm',
        data: {
            viewfile: viewfile
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