<option value="0" selected="selected">Select category</option>
@if(!empty($result))
    @foreach($result as $row )
    <option value="{{ $row->id }}"> {{ $row->crm_sub_category_name }}</option>
    @endforeach
@endif
