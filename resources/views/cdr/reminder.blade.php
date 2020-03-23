<div class="modal-content">
<div class="modal-header">
        <h5 class="modal-title" id="verifyModalContent_title">Reminder</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <form>
            <input type="hidden" id="cdrid" name="cdrid" value="{{$id}}">
            <div class="form-group">
                <label for="recipient-name-1" class="col-form-label"> Reminder date:</label>
                <input type="text" class="form-control" id="startdate" name="startdate">
            </div>
            <div class="form-group">
                <label for="recipient-name-1" class="col-form-label"> Reminder Time:</label>
                <input type="text" class="form-control" id="Timepicker" name="Timepicker">
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="contactsubmit">Send message</button>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        $('#startdate').pickadate();
        $('#Timepicker').pickatime();
        $("#contactsubmit").click(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('addReminder') }}",
                method: 'post',
                data: {
                    startdate: $('#startdate').val(),
                    cdrid: $('#cdrid').val(),
                    Timepicker: $('#Timepicker').val()
                },
                success: function(data){
                    $.each(data.errors, function(key, value){
                        $('.alert-danger').show();
                        $('.alert-danger').append('<p>'+value+'</p>');
                    });
                    if( data.success ){
                        alert( 'Success' );
                        $('#ModalContent').modal('hide');
                    }

                }

            });
        });

    });
</script>