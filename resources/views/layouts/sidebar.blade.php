
  <div class="side-content-wrap">
            <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
                <ul class="navigation-left">
                <li class="nav-item {{ request()->is('dashboard/*') ? 'active' : '' }}" data-item="dashboard">
                        <a class="nav-item-hold" href="{{url('dashboard_version_1')}}">
                            <i class="nav-icon i-Bar-Chart"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="calldetails">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">Call Details</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-File-Clipboard-File--Text"></i>
                            <span class="nav-text">Forms</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{route('DidList')}}">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">Did</span>
                        </a>
                        <!-- <div class="triangle"></div> -->
                        <!-- <div class="triangle"></div> -->
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{route('UserList')}}">
                            <i class="nav-icon i-Administrator"></i>
                            <span class="nav-text">Users</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="insightcrm">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">Insight CRM</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{url('contacts')}}">
                            <i class="nav-icon i-Administrator"></i>
                            <span class="nav-text">Contacts</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="">
                            <i class="nav-icon i-Administrator"></i>
                            <span class="nav-text">To Do List</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="">
                            <i class="nav-icon i-Administrator"></i>
                            <span class="nav-text">Send SMS</span>
                        </a>
                        <div class="triangle"></div>
                    </li>

                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{url('conference')}}">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">Dial Out Conference</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{url('notification')}}">
                            <i class="nav-icon i-Bell"></i>
                            <span class="nav-text">Notifications</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{url('reminder')}}">
                            <i class="nav-icon i-Bell"></i>
                            <span class="nav-text">Reminders</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{url('languages')}}">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">Languages</span>
                        </a>
                        <div class="triangle"></div>
                    </li>                    

                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{url('ivrmenulist')}}">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">IVR Menu</span>
                        </a>
                        <div class="triangle"></div>
                    </li>

                </ul>
            </div>

            <div class="sidebar-left-secondary rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
                <!-- Submenu Dashboards -->
                <ul class="childNav" data-parent="calldetails">
                    <li class="nav-item">
                        <a class="{{ Route::currentRouteName()=='cdrreport' ? 'open' : '' }}" href="{{url('cdrreport')}}" >
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">CDR Report</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="{{ Route::currentRouteName()=='livecalls' ? 'open' : '' }}" href="{{url('livecalls')}}">
                            <i class="nav-icon i-Clock-3"></i>
                            <span class="item-name">Incoming Calls</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('cdrreportout')}}" class="{{ Route::currentRouteName()=='cdrreportout' ? 'open' : '' }}">
                            <i class="nav-icon i-Clock-4"></i>
                            <span class="item-name">Voice Out</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ Route::currentRouteName()=='voicemail' ? 'open' : '' }}" href="{{url('voicemail')}}" >
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Voice Mails</span>
                        </a>
                    </li>


                </ul>
                <ul class="childNav" data-parent="insight">
                    <li class="nav-item">
                        <a class="{{ Route::currentRouteName()=='operator' ? 'open' : '' }}" href="{{url('operator')}}" >
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">IVR Department</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="{{ Route::currentRouteName()=='operatordept' ? 'open' : '' }}" href="{{url('operatordept')}}">
                            <i class="nav-icon i-Clock-3"></i>
                            <span class="item-name">Operator Departments</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="{{ Route::currentRouteName()=='blacklist' ? 'open' : '' }}" href="{{url('blacklist')}}">
                            <i class="nav-icon i-Clock-3"></i>
                            <span class="item-name">Black list</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('holiday')}}" class="{{ Route::currentRouteName()=='holiday' ? 'open' : '' }}">
                            <i class="nav-icon i-Clock-4"></i>
                            <span class="item-name">Set Holiday</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ Route::currentRouteName()=='cdrtags' ? 'open' : '' }}" href="{{url('cdrtags')}}" >
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">CDR Tags</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ Route::currentRouteName()=='cdrreportarchive' ? 'open' : '' }}" href="#" >
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Set Office Hours</span>
                        </a>
                    </li>

                </ul>
                <ul class="childNav" data-parent="insightcrm">
                    <li class="nav-item">
                        <a href="{{url('/crm/category-list')}}">
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('/crm/sub-category-list')}}">
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Sub Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('/crm/status-list')}}">
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Status</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a>
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Email & SMS Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a>
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Email & Templates</span>
                        </a>
                    </li>

                </ul>

            </div>
            <div class="sidebar-overlay"></div>
        </div>
        <!--=============== Left side End ================-->
