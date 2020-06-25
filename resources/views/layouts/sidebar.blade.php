<style type="text/css">
    .nav-item-hold{
            background-image: -webkit-linear-gradient(-154deg, #004e92 0%, #000428 100%);
    background: linear-gradient(-154deg, #004e92 0%, #000428 100%);
    color: white!important;
    }
    .btn-round {
    color: #666;
    padding: 3px 9px;
    font-size: 13px;
    line-height: 1.5;
    background-color: #fff;
    border-color: #ccc;
    border-radius: 50px;
}
.mine
{
    z-index: 1000!important;
    position: absolute!important;
    right: 15px!important;
    top: 104px!important;
    width: 50%;
}
</style>
    <div class="side-content-wrap">
            <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
                <ul class="navigation-left">
                    <li class="nav-item">
                        <a class="nav-item-hold" href="{{route('home')}}">
                            <i class="nav-icon i-Bar-Chart"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item" data-item="">
                        <a class="nav-item-hold" href="{{url('notification')}}">
                            <i class="nav-icon i-Bell"></i>
                            <span class="nav-text">Help</span>
                        </a>
                        <div class="triangle"></div>
                    </li>

                    @if(Auth::user()->usertype == 'admin')
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('LiveCalls')}}">
                                <i class="nav-icon i-Telephone"></i>
                                <span class="nav-text">Live Calls</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="calldetails">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Calls & Reports</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('UserList')}}">
                                <i class="nav-icon i-Administrator"></i>
                                <span class="nav-text">IVR Users</span>
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
                        <li class="nav-item" data-item="insightivr">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Arrow-From"></i>
                                <span class="nav-text">Insight Ivr</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="ivrmgmt">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Ivr Management</span>
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
                        <li class="nav-item" data-item="pbx">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Clock-4"></i>
                                <span class="nav-text">Voice Out</span>
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
                        <!-- Calls & Reports -->
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('AccessLogs')}}">
                                <i class="nav-icon i-Security-Check"></i>
                                <span class="nav-text">Access Logs</span>
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
                        <li class="nav-item ">
                            <a class="nav-item-hold" href="{{route('LiveCalls')}}">
                                <i class="nav-icon i-Clock-3"></i>
                                <span class="item-name">Live Calls</span>
                            </a>
                        </li>
                        <li class="nav-item" data-item="calldetails">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Calls & Reports</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        @if(Auth::user()->load('accountdetails')->accountdetails != null && Auth::user()->load('accountdetails')->accountdetails->crm == 1)
                        <li class="nav-item" data-item="settings">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">CRM</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        @endif
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{url('reminder')}}">
                                <i class="nav-icon i-Bell"></i>
                                <span class="nav-text">Reminders</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="insightivr">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Arrow-From"></i>
                                <span class="nav-text">Insight Ivr</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="">
                            <a href="{{url('cdrreportout')}}" class="nav-item-hold">
                                <i class="nav-icon i-Clock-4"></i>
                                <span class="item-name">Voice Out</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('OperatorShifts')}}">
                                <i class="nav-icon i-Over-Time"></i>
                                <span class="nav-text">Operator Shifts</span>
                            </a>
                            <div class="triangle"></div>
                        </li> -->
                        <li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{route('OperatorList')}}">
                                <i class="nav-icon i-Administrator"></i>
                                <span class="nav-text">Operator Account</span>
                            </a> 
                        </li>
                        <!-- <li class="nav-item" data-item="">
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
                        </li> -->
                        <!-- <li class="nav-item" data-item="">
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
                        </li>-->
                        <!-- <li class="nav-item">
                            <a href="{{url('holiday')}}" class="nav-item-hold">
                                <i class="nav-icon i-Clock-4"></i>
                                <span class="nav-text">Holiday</span>
                            </a>
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
                        </li> -->
                    @endif
					@if(Auth::user()->usertype == 'reseller')
						<li class="nav-item" data-item="calldetails">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Calls & Reports</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
						<li class="nav-item ">
                            <a class="nav-item-hold" href="{{route('LiveCalls')}}">
                                <i class="nav-icon i-Clock-3"></i>
                                <span class="item-name">Live Calls</span>
                            </a>
                        </li>
						@endif
                    @if(Auth::user()->usertype == 'operator')
						<li class="nav-item" data-item="calldetails">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">Calls & Reports</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        @if(Auth::user()->load('accountdetails')->accountdetails->crm == 1)
                        <li class="nav-item" data-item="settings">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Library"></i>
                                <span class="nav-text">CRM</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        @endif
						<li class="nav-item" data-item="">
                            <a class="nav-item-hold" href="{{url('reminder')}}">
                                <i class="nav-icon i-Bell"></i>
                                <span class="nav-text">Reminders</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                        <li class="nav-item" data-item="insightivr">
                            <a class="nav-item-hold" href="#">
                                <i class="nav-icon i-Arrow-From"></i>
                                <span class="nav-text">Insight Ivr</span>
                            </a>
                            <div class="triangle"></div>
                        </li>
                    @endif
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
                    <li class="nav-item">
                        <a class="{{ Route::currentRouteName()=='voicemail' ? 'open' : '' }}" href="{{url('voicemail')}}" >
                            <i class="nav-icon i-Over-Time"></i>
                            <span class="item-name">Voice Mails</span>
                        </a>
                    </li>
                </ul>
                <ul class="childNav" data-parent="insightivr">
                    @if(Auth::user()->usertype == 'admin')
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
                    <li class="nav-item">
                        <a href="{{url('ivr_menu')}}">
                            <span class="item-name">IVR Menu</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->usertype == 'groupadmin')
                    <li class="nav-item">
                        <a href="{{route('dashboard')}}">
                            <span class="item-name">Dashboard</span>
                        </a> 
                    </li>
                    <li class="nav-item">
                        <a href="{{route('OperatorGroup')}}">
                            <span class="item-name">Operator Departments</span>
                        </a> 
                    </li>
                    <li class="nav-item">
                        <a href="{{route('OperatorShifts')}}">
                            <span class="item-name">Operator Shifts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('Voicemail')}}">
                            <span class="item-name">Voicemails</span>
                        </a> 
                    </li>
                    <li class="nav-item">
                        <a href="{{url('contacts')}}">
                            <span class="item-name">Contacts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('holiday')}}">
                            <span class="item-name">Holiday</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('BlackList')}}">
                            <span class="item-name">Blacklist</span>
                        </a> 
                    </li>
                    <li class="nav-item" data-item="">
                        <a href="{{url('cdr_tags')}}">
                            <span class="item-name">Cdr Tags
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('conference')}}">
                            <span class="item-name">Dial Out Conference</span>
                        </a>
                        </li>
                    @endif
                    @if(Auth::user()->usertype == 'operator')
                    <li class="nav-item" data-item="">
                        <a href="{{url('cdr_tags')}}">
                            <span class="item-name">Cdr Tags
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
                <ul class="childNav" data-parent="ivrmgmt">
                    <li class="nav-item">
                        <a href="{{route('DidList')}}">
                            <span class="item-name">Dids</span>
                        </a> 
                    </li>
                    <li class="nav-item">
                        <a href="{{route('GateWay')}}">
                            <span class="item-name">PRI Gateway</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('languages')}}">
                            <span class="item-name">Multi Language</span>
                        </a>
                    </li> 
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
                    <li class="nav-item">
                        <a href="{{route('pushApi')}}">
                            <span class="item-name">Push Notification</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('smsApi')}}">
                            <span class="nav-text">Sms Api</span>
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
                    <li class="nav-item">
                        <a href="{{url('cdrreportout')}}">
                            <span class="item-name">Cdr Report</span>
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

                <?php
                if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') { ?>
                    <ul class="childNav" data-parent="settings">
                        <li class="nav-item">
                            <a href="{{url('settings/category')}}">
                                <span class="item-name">Category</span>
                            </a>
                        </li>  

                        <li class="nav-item">
                            <a href="{{url('product')}}">
                                <span class="item-name">Products</span>
                            </a>
                        </li>  

                        <li class="nav-item">
                            <a href="{{url('cdrreport_lists')}}">
                                <span class="item-name">List Lead</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('cdrreport/converted')}}">
                                <span class="item-name">Customer</span>
                            </a>
                        </li>  

                        <li class="nav-item">
                            <a href="{{url('proposal/list')}}">
                                <span class="item-name">Proposal</span>
                            </a>
                        </li>   

                        <li class="nav-item">
                            <a href="{{url('invoice/list')}}">
                                <span class="item-name">Invoice</span>
                            </a>
                        </li> 

                        <li class="nav-item">
                            <a href="{{url('email_template/list')}}">
                                <span class="item-name">Email Template</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('sms_template/list')}}">
                                <span class="item-name">SMS Template</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('sms_api/list')}}">
                                <span class="item-name">SMS Api</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('terms_condition/add')}}">
                                <span class="item-name">Terms And Condition</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('transfer_leads/index')}}">
                                <span class="item-name">Transfer Leads</span>
                            </a>
                        </li>


                    </ul>    
                <?php }else{ ?>
                    <ul class="childNav" data-parent="settings">
                        <li class="nav-item">
                            <a href="{{url('cdrreport_lists')}}">
                                <span class="item-name">List Lead</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('cdrreport/converted')}}">
                                <span class="item-name">Customer</span>
                            </a>
                        </li>  

                        <li class="nav-item">
                            <a href="{{url('proposal/list')}}">
                                <span class="item-name">Proposal</span>
                            </a>
                        </li>   

                        <li class="nav-item">
                            <a href="{{url('invoice/list')}}">
                                <span class="item-name">Invoice</span>
                            </a>
                        </li> 

                        <li class="nav-item">
                            <a href="{{url('email_template/list')}}">
                                <span class="item-name">Email Template</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{url('sms_template/list')}}">
                                <span class="item-name">SMS Template</span>
                            </a>
                        </li> 
  
                    </ul> 
                <?php }?>
                

            </div>
            <div class="sidebar-overlay"></div>
        </div>
        <!--=============== Left side End ================-->
        <?php
        use Illuminate\Support\Facades\DB;
        date_default_timezone_set('Asia/Kolkata'); 

        $list_remainder = DB::table('lead_reminders')
                    ->where('lead_reminders.user_id','=',Auth::user()->id)
                    ->whereDate('lead_reminders.date', '>=', date('Y-m-d'))
                    ->leftJoin('cdrreport_lead', 'cdrreport_lead.id', '=', 'lead_reminders.cdrreport_lead_id')
                    ->select('lead_reminders.*','cdrreport_lead.email')
                    ->get();

        //print_r($list_remainder);

        $time = date('H:i', strtotime("+30 minutes"));
        $date = date('Y-m-d');
       // echo $date."::".$time;
        $i = 0;
        foreach ($list_remainder as $key => $value) {
            
            if ($value->date == $date) {

                //print_r($value);
                //echo "<br>";
                $cleantime=substr($value->time,0,5);
                //echo $cleantime;
                
                if ($cleantime == $time) { ?>

                    <div class="alert alert-danger mine" id="myDIV<?php echo $i;?>">
                        <h3>Remainder</h3>
                        <h4>Title is: <?php echo $value->title;?></h4>
                        <p>
                            Your Remainder is after 1/2 Hour
                        </p>
                        <p>
                            Description : <?php echo $value->task;?>
                        </p>
                        <hr class="message-inner-separator">
                        <button class="caption-helper btn btn-round" onclick="myFunctions('myDIV<?php echo $i;?>')">Get It</button>
                    </div>
                <?php }
            } $i++;
        }

        ?>
<script type="text/javascript">
    function myFunctions(id) {
      var x = document.getElementById(id);
      if (x.style.display === "none") {
        x.style.display = "block";
      } else {
        x.style.display = "none";
      }
    }

</script>
