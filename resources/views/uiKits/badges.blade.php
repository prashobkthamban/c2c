@extends('layouts.master')

@section('main-content')
     <div class="breadcrumb">
                <h1>Badges</h1>
                <ul>
                    <li><a href="">UI Kits</a></li>
                    <li>Badges</li>
                </ul>
            </div>

            <div class="separator-breadcrumb border-top"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="card-title mb-4">Badge outline</div>

                            <span class="badge badge-pill badge-outline-primary p-2 m-1">Primary</span>
                            <span class="badge badge-pill badge-outline-secondary p-2 m-1">Secondary</span>
                            <span class="badge badge-pill badge-outline-success p-2 m-1">Success</span>
                            <span class="badge badge-pill badge-outline-danger p-2 m-1">Danger</span>
                            <span class="badge badge-pill badge-outline-warning p-2 m-1">Warning</span>
                            <span class="badge badge-pill badge-outline-info p-2 m-1">Info</span>
                            <span class="badge badge-pill badge-outline-dark p-2 m-1">Dark</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-4">Regular Badges</div>

                            <span class="badge badge-primary mr-1">Primary</span>
                            <span class="badge badge-secondary mr-1">Secondary</span>
                            <span class="badge badge-success mr-1">Success</span>
                            <span class="badge badge-danger mr-1">Danger</span>
                            <span class="badge badge-warning mr-1">Warning</span>
                            <span class="badge badge-info mr-1">Info</span>
                            <span class="badge badge-light mr-1">Light</span>
                            <span class="badge badge-dark mr-1">Dark</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="card-title mb-4">Badge Pill</div>

                            <span class="badge badge-pill badge-primary mr-1">Primary</span>
                            <span class="badge badge-pill badge-secondary mr-1">Secondary</span>
                            <span class="badge badge-pill badge-success mr-1">Success</span>
                            <span class="badge badge-pill badge-danger mr-1">Danger</span>
                            <span class="badge badge-pill badge-warning mr-1">Warning</span>
                            <span class="badge badge-pill badge-info mr-1">Info</span>
                            <span class="badge badge-pill badge-light mr-1">Light</span>
                            <span class="badge badge-pill badge-dark mr-1">Dark</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="card-title mb-4">Badge Pill with Padding</div>

                            <span class="badge badge-pill badge-primary p-2 m-1">Primary</span>
                            <span class="badge badge-pill badge-secondary p-2 m-1">Secondary</span>
                            <span class="badge badge-pill badge-success p-2 m-1">Success</span>
                            <span class="badge badge-pill badge-danger p-2 m-1">Danger</span>
                            <span class="badge badge-pill badge-warning p-2 m-1">Warning</span>
                            <span class="badge badge-pill badge-info p-2 m-1">Info</span>
                            <span class="badge badge-pill badge-light p-2 m-1">Light</span>
                            <span class="badge badge-pill badge-dark p-2 m-1">Dark</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="card-title mb-4">Badge link</div>

                            <a href="#" class="badge badge-primary mr-1">Primary</a>
                            <a href="#" class="badge badge-secondary mr-1">Secondary</a>
                            <a href="#" class="badge badge-success mr-1">Success</a>
                            <a href="#" class="badge badge-danger mr-1">Danger</a>
                            <a href="#" class="badge badge-warning mr-1">Warning</a>
                            <a href="#" class="badge badge-info mr-1">Info</a>
                            <a href="#" class="badge badge-light mr-1">Light</a>
                            <a href="#" class="badge badge-dark mr-1">Dark</a>
                        </div>
                    </div>
                </div>
            </div>
@endsection

@section('page-js')

@endsection
