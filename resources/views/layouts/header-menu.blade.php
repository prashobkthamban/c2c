    <div class="main-header">
            <div class="logo">
                <img src="{{asset('assets/images/logo.png')}}" alt="">
            </div>

            <div class="menu-toggle">
                <div></div>
                <div></div>
                <div></div>
            </div>

            <div class="d-flex align-items-center">
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <i class="search-icon text-muted i-Magnifi-Glass1"></i>
                </div>
            </div>

            <div style="margin: auto"></div>

            <div class="header-part-right">
                <!-- Full screen toggle -->
                <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i>
                <!-- Grid menu Dropdown -->
                <div class="dropdown widget_dropdown">
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div class="menu-icon-grid">
                            <a href="#"><i class="i-Shop-4"></i> Home</a>
                            <a href="#"><i class="i-Library"></i> UI Kits</a>
                            <a href="#"><i class="i-Drop"></i> Apps</a>
                            <a href="#"><i class="i-File-Clipboard-File--Text"></i> Forms</a>
                            <a href="#"><i class="i-Checked-User"></i> Sessions</a>
                            <a href="#"><i class="i-Ambulance"></i> Support</a>
                        </div>
                    </div>
                </div>
                   <!-- Notificaiton -->

                <div class="dropdown">
                    <div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php $notifyList = unreadNotification();
                        //dd($notifyList); ?>
                        <span class="badge badge-primary notification_count"><?php echo $notifyList['not_count']; ?></span>
                        <i class="i-Bell text-muted header-icon"></i>
                    </div>
                    <!-- Notification dropdown -->
                    
                    <div class="dropdown-menu dropdown-menu-right notification-dropdown rtl-ps-none" aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">
                        @foreach($notifyList['not_list'] as $listOne)
                        <div class="dropdown-item d-flex" id="not_id_{{$listOne->id}}">
                            <div class="notification-icon">
                                <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                            </div>
                            <div class="notification-details flex-grow-1">
                                <p class="m-0 d-flex align-items-center">
                                    <span>New message</span>
                                    <span class="badge badge-pill badge-primary ml-1 mr-1">new</span>
                                    <span class="flex-grow-1"></span>
                                    <!-- <span class="text-small text-muted ml-auto">10 sec ago</span> -->
                                </p>
                                <p class="text-small text-muted m-0">{{$listOne->fromusername}}: {{$listOne->title}}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                </div>
                <!-- Notificaiton End -->

                <!-- User avatar dropdown -->
                @guest
                <li class="nav-item">
                    <a href="{{ route('login') }}">
                        <i class="nav-icon i-Checked-User"></i>
                        <span class="item-name">Sign in</span>
                    </a>
                </li>
                @else
                <div class="dropdown">
                    <div  class="user col align-self-end">
                        <img src="{{asset('assets/images/faces/1.jpg')}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <div class="dropdown-header">
                                <i class="i-Lock-User mr-1"></i> {{ Auth::user()->username }} ({{Auth::user()->usertype}})
                            </div>
                            <a class="dropdown-item" href="{{route('myProfile')}}">Profile</a>
                            @if(Auth::user()->usertype == 'groupadmin')
                            <a class="dropdown-item" href="{{route('Billing')}}">My Bill/Balance</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Sign out</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                @endguest
            </div>

        </div>
        <!-- header top menu end -->
