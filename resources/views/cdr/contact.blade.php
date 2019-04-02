<div class="modal-content">
<div class="modal-header">
        <h5 class="modal-title" id="verifyModalContent_title">Contact</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <form>
        @csrf
        <div class="alert alert-danger" style="display:none"></div>
        <input type="hidden" name="rowid" value="{{$id}}" id="rowid">
    <div class="modal-body">

            <div class="form-group">
                <label for="recipient-name-1" class="col-form-label">Fist name:</label>
                <input type="text" class="form-control" name="fname" id="fname">
            </div>
            <div class="form-group">
                <label for="recipient-name-1" class="col-form-label">Last name:</label>
                <input type="text" class="form-control" name="lname" id="lname">
            </div>
            <div class="form-group">
                <label for="recipient-name-1" class="col-form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email">
            </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary"  id="contactsubmit">Create</button>
    </div>
    </form>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        $("#contactsubmit").click(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('addContact') }}",
                method: 'post',
                data: {
                    fname: $('#fname').val(),
                    lname: $('#lname').val(),
                    email: $('#email').val(),
                    rowid: $('#rowid').val()
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