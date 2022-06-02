<?php $customers = getCustomers(); ?>
<div class="row">
    <div id="filter-panel" class="col-lg-12 col-md-12 filter-panel collapse {{count($requests) > 0 ? 'show' : ''}}">
        <div class="card mb-2">
            <div class="card-body">
                <div>
                    <h5 class="ml-3">Search Panel</h5></br>
                    <form class="form" role="form" id="filter_form">
                        <div class="row" style="margin-right: 24px;margin-left: 24px;">
                            <div class="col-md-4" id="customer_div">
                                <label class="filter-col" for="pref-perpage">Customers</label>
                                <select name="customer" class="form-control" id="customer_id">
                                    <option value="">All</option>
                                    @if(!empty($customers))
                                    @foreach($customers as $customer )
                                    <option value="{{$customer->id}}" @if(isset($requests['customer']) && $customer->id == $requests['customer']) selected @endif>{{$customer->name}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top: 24px;">
                            <button type="submit" id="search_btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                            <button type="button" id="reload_page_btn" class="btn btn-outline-secondary" name="clear_btn">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="customizer" title="Search" style="top:75px">
    <a href="#" data-toggle="collapse" data-target="#filter-panel">
        <div class="handle collapsed">
            <i class="i-Search-People"></i>
        </div>
    </a>
</div>