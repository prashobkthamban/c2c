
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
                    @if(Auth::user()->usertype == 'admin')
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('DidList')}}">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Dids</span>
                            </a> 
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('UserList')}}">
                                <i class="nav-icon i-Administrator"></i>
                                <span class="nav-text">IVR Users</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('loginAccounts')}}">
                                <i class="nav-icon i-Administrator"></i>
                                <span class="nav-text">Login Accounts</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                       <!--  <li class="nav-item" data-item="">
                            <a href="{{url('cdrreportout')}}" class="{{ Route::currentRouteName()=='cdrreportout' ? 'open' : '' }}"> 
                            <a href="{{url('cdrreportout')}}" class="nav-item-hold">
                                <i class="nav-icon i-Clock-4"></i>
                                <span class="item-name">Voice Out</span>
                            </a>
                        </li> -->
                        <li class="nav-item" data-item="pbx">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Headphone"></i>
                                <span class="nav-text">Voice Out</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('Billing')}}">
                                <i class="nav-icon i-Credit-Card"></i>
                                <span class="nav-text">Recharge & Billing</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a href="{{url('cdrreportout')}}" class="{{ Route::currentRouteName()=='cdrreportout' ? 'open' : '' }}"> 
                            <a href="{{url('cdrreportout')}}" class="nav-item-hold">
                                <i class="nav-icon i-Clock-4"></i>
                                <span class="item-name">Voice Out</span>
                            </a>
                        </li> 
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{url('ivr_menu')}}">
                                <i class="nav-icon i-Arrow-From"></i>
                                <span class="nav-text">IVR Menu</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="user_dept">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">User Department</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{url('languages')}}">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Multi Language</span>
                            </a>
                            <div class="triangle"></div>
                        </li>    
                        <li class="nav-item" data-item="voicefiles">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Voicefiles</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('CoperateGroup')}}">
                                <i class="nav-icon i-Administrator"></i>
                                <span class="nav-text">Add Coperate</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="calldetails">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Call & Reports</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('AccessLogs')}}">
                                <i class="nav-icon i-Security-Check"></i>
                                <span class="nav-text">Access Logs</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('LiveCalls')}}">
                                <i class="nav-icon i-Telephone"></i>
                                <span class="nav-text">Live Calls</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('LiveCalls')}}">
                                <i class="nav-icon i-Telephone"></i>
                                <span class="nav-text">PRI Gateway</span>
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
                            <a class="nav-item-hold" href="{{url('acc_call_summary')}}">
                                <i class="nav-icon i-File-TXT"></i>
                                <span class="nav-text">Account Call Summary</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{url('dashboard_note')}}">
                                <i class="nav-icon i-File-TXT"></i>
                                <span class="nav-text">Dashboard Announcement</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                    @endif

                    @if(Auth::user()->usertype == 'groupadmin')
                        <li class="nav-item" data-item="calldetails">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Call & Reports</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <!-- <a href="{{url('cdrreportout')}}" class="{{ Route::currentRouteName()=='cdrreportout' ? 'open' : '' }}"> -->
                            <a href="{{url('cdrreportout')}}" class="nav-item-hold">
                                <i class="nav-icon i-Clock-4"></i>
                                <span class="item-name">Voice Out</span>
                            </a>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{url('reminder')}}">
                                <i class="nav-icon i-Bell"></i>
                                <span class="nav-text">Reminders</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('OperatorList')}}">
                                <i class="nav-icon i-Administrator"></i>
                                <span class="nav-text">Operator Account</span>
                            </a> 
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('OperatorGroup')}}">
                                <i class="nav-icon i-Folder-Organizing"></i>
                                <span class="nav-text">Operator Departments</span>
                            </a> 
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{url('contacts')}}">
                                <i class="nav-icon i-Administrator"></i>
                                <span class="nav-text">Contacts</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('Voicemail')}}">
                                <i class="nav-icon i-Mail-2"></i>
                                <span class="nav-text">Voicemails</span>
                            </a> 
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('BlackList')}}">
                                <i class="nav-icon i-Security-Block"></i>
                                <span class="nav-text">Blacklist</span>
                            </a> 
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{url('notification')}}">
                                <i class="nav-icon i-Bell"></i>
                                <span class="nav-text">Notifications</span>
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
                            <a class="nav-item-hold" href="{{url('cdr_tags')}}">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Cdr Tags
                                </span>
                            </a>
                            <div class="triangle"></div>
                        </li>


                    @endif

                    <!-- 
                    <li class="nav-item" data-item="insight">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">Insight IVR</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{url('operator')}}">
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
                    <li class="nav-item" data-item="insightcrm">
                        <a class="nav-item-hold" href="#">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">Insight CRM</span>
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
                        <a class="nav-item-hold" href="{{url('notification')}}">
                            <i class="nav-icon i-Bell"></i>
                            <span class="nav-text">Notifications</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{url('ivrmenulist')}}">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">IVR Menu</span>
                        </a>
                        <div class="triangle"></div>
                    </li> -->
                    

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
                    <li class="nav-item">
                        <a class="{{ Route::currentRouteName()=='cdrreportarchive' ? 'open' : '' }}" href="{{url('cdrreportarchive')}}" >
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Archived CDR Report</span>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="{{ Route::currentRouteName()=='livecalls' ? 'open' : '' }}" href="{{url('livecalls')}}">
                            <i class="nav-icon i-Clock-3"></i>
                            <span class="item-name">Incoming Calls</span>
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
                        <a href="{{route('LeadList')}}">
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Leads</span>
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

                <ul class="childNav" data-parent="user_dept">
                    <li class="nav-item">
                        <a href="{{url('optdept_list')}}">
                            <span class="item-name">Operator Department</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('nonoperator_list')}}">
                            <span class="item-name">Non Operator Department</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('sms_list')}}">
                            <span class="item-name">Operator Dpt Configure SMS</span>
                        </a>
                    </li>
               
                </ul>

                <ul class="childNav" data-parent="pbx">
                    <li class="nav-item">
                        <a href="{{route('PbxExtension')}}">
                            <span class="item-name">PBX Extension</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('PbxDid')}}">
                            <span class="item-name">PBX DIDs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('PbxRingGroups')}}">
                            <span class="item-name">PBX RingGroups</span>
                        </a>
                    </li>
               
                </ul>

                <ul class="childNav" data-parent="voicefiles">
                    <li class="nav-item">
                        <a href="{{url('voice_files')}}">
                            <span class="item-name">Voicefiles Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('general_files')}}">
                            <span class="item-name">General Files</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('moh_listings')}}">
                            <span class="item-name">MOH Files</span>
                        </a>
                    </li>
               
                </ul>

            </div>
            <div class="sidebar-overlay"></div>
        </div>
        <!--=============== Left side End ================-->
