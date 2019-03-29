<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gull - Laravel + Bootstrap 4 admin template</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
@yield('before-css')
    {{-- theme css --}}
<link id="gull-theme" rel="stylesheet" href="{{asset('assets/styles/css/themes/lite-purple.min.css')}}">
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/perfect-scrollbar.css')}}">
 {{-- page specific css --}}
 @yield('page-css')
</head>

<body class="text-left">
    @php
        $layout = session('layout');
    @endphp

  <!-- ============ Compact Layout start ============= -->
    @if ($layout=="compact")
    {{-- compact layout --}}
         <div class="app-admin-wrap layout-sidebar-compact sidebar-dark-purple sidenav-open clearfix">
              @include('layouts.sidebar-compact')
       {{-- end of left sidebar --}}


        <!-- ============ Body content start ============= -->
        <div class="main-content-wrap d-flex flex-column">
        @include('layouts.header-menu')
      {{-- end of header menu --}}
      <div class="main-content">
           @yield('main-content')
      </div>

            @include('layouts.footer')
        </div>
        <!-- ============ Body content End ============= -->
    </div>
    <!--=============== End app-admin-wrap ================-->

    <!-- ============ Search UI Start ============= -->
  @include('layouts.search')
    <!-- ============ Search UI End ============= -->

     @include('layouts.compact-sidebar-customizer')



       <!-- ============ Compact Layout End ============= -->


  <!-- ============ Horizontal Layout start ============= -->

 @elseif($layout=="horizontal")
    {{-- normal layout --}}
        <div class="app-admin-wrap layout-horizontal-bar clearfix">
             @include('layouts.header-menu')
      {{-- end of header menu --}}



       @include('layouts.horizontal-bar')
       {{-- end of left sidebar --}}

        <!-- ============ Body content start ============= -->
<div class="main-content-wrap  d-flex flex-column">
<div class="main-content">
    @yield('main-content')
</div>

            @include('layouts.footer')
        </div>
        <!-- ============ Body content End ============= -->
    </div>
    <!--=============== End app-admin-wrap ================-->

    <!-- ============ Search UI Start ============= -->
  @include('layouts.search')
    <!-- ============ Search UI End ============= -->

    @include('layouts.horizontal-customizer')


  <!-- ============ Horizontal Layout End ============= -->






         <!-- ============ Large SIdebar Layout start ============= -->
    @elseif($layout=="normal")
    {{-- normal layout --}}
        <div class="app-admin-wrap layout-sidebar-large clearfix">
             @include('layouts.header-menu')
      {{-- end of header menu --}}



       @include('layouts.sidebar')
       {{-- end of left sidebar --}}

        <!-- ============ Body content start ============= -->
        <div class="main-content-wrap sidenav-open d-flex flex-column">
<div class="main-content">
    @yield('main-content')
</div>

            @include('layouts.footer')
        </div>
        <!-- ============ Body content End ============= -->
    </div>
    <!--=============== End app-admin-wrap ================-->

    <!-- ============ Search UI Start ============= -->
  @include('layouts.search')
    <!-- ============ Search UI End ============= -->

    @include('layouts.large-sidebar-customizer')


      <!-- ============ Large Sidebar Layout End ============= -->

      @else
       <!-- ============ Large SIdebar Layout start ============= -->

    {{-- normal layout --}}
        <div class="app-admin-wrap layout-sidebar-large clearfix">
             @include('layouts.header-menu')
      {{-- end of header menu --}}



       @include('layouts.sidebar')
       {{-- end of left sidebar --}}

        <!-- ============ Body content start ============= -->
        <div class="main-content-wrap sidenav-open d-flex flex-column">
<div class="main-content">
    @yield('main-content')
</div>

            @include('layouts.footer')
        </div>
        <!-- ============ Body content End ============= -->
    </div>
    <!--=============== End app-admin-wrap ================-->

    <!-- ============ Search UI Start ============= -->
  @include('layouts.search')
    <!-- ============ Search UI End ============= -->

    @include('layouts.large-sidebar-customizer')


      <!-- ============ Large Sidebar Layout End ============= -->
    @endif




{{-- common js --}}
<script src="{{asset('assets/js/common-bundle-script.js')}}"></script>
    {{-- page specific javascript --}}
    @yield('page-js')

    {{-- theme javascript --}}
    {{-- <script src="{{mix('assets/js/es5/script.js')}}"></script> --}}
    <script src="{{asset('assets/js/es5/script.min.js')}}"></script>


    @if ($layout=='compact')
    <script src="{{asset('assets/js/es5/sidebar.compact.script.min.js')}}"></script>


    @elseif($layout=='normal')
<script src="{{asset('assets/js/es5/sidebar.large.script.min.js')}}"></script>


@elseif($layout=='horizontal')
<script src="{{asset('assets/js/sidebar-horizontal.script.js')}}"></script>


@else
<script src="{{asset('assets/js/es5/sidebar.large.script.min.js')}}"></script>

    @endif



    <script src="{{asset('assets/js/es5/customizer.script.min.js')}}"></script>

    {{-- laravel js --}}
    {{-- <script src="{{mix('assets/js/laravel/app.js')}}"></script> --}}

    @yield('bottom-js')
<script>
    $('#zero_configuration_table').dataTable( {
        paging: false

    } );
</script>
    <div class="modal fade" id="ModalContent" tabindex="-1" role="dialog" aria-labelledby="ModalContent" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyModalContent_title">New message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="recipient-name-1" class="col-form-label">Recipient:</label>
                            <input type="text" class="form-control" id="recipient-name-1">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Message:</label>
                            <textarea class="form-control" id="message-text"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send message</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
