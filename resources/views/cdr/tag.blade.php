<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="verifyModalContent_title">Tag</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <form>
            <div class="form-group">
                <label for="recipient-name-1" class="col-form-label">Tag:</label>
                <select name="tagid" id="tagid" class="form-control">
                    @foreach($tags as $t)
                    <option value="{{$t->id}}">{{$t->tag}}</option>
                    @endforeach
                </select>
            </div>

        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="contactsubmit">Create</button>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        $("#contactsubmit").click(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('addTag') }}",
                method: 'post',
                data: {
                    tagid: $('#tagid').val()
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