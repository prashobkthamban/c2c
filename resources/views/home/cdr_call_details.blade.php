<table class="display table table-striped table-bordered">
    <thead>
        <tr>
            <th>Operator</th>
            <th>Date&Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($data))
        @foreach($data as $row)
        <tr>
            <td>{{$row->opername}}</td>
            <td>{{$row->date_time}}</td>
            <td>{{$row->status}}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>