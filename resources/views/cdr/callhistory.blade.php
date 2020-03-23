<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="verifyModalContent_title">Call History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">

        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date Time</th>
                    <th scope="col">Operator Name</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($callhistory as $t)
                    <tr>
                        <th scope="row">1</th>
                        <td>{{$t->date_time}}</td>
                        <td>{{$t->opername}}</td>
                        <td>
                            @if($t->status == 'CANCEL')
                                <span class="badge badge-info">{{$t->status}}</span>
                                @elseif($t->status == 'NOANSWER')
                                <span class="badge badge-warning">{{$t->status}}</span>
                                @else
                                <span class="badge badge-success">{{$t->status}}</span>
                                @endif
                        </td>




                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {

    });
</script>