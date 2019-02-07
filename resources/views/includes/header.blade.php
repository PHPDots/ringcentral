<header>           
    <div class="top-bar-section">  
        <div class="container-fluid">
            <div class="header-top">
                <div class="sidebar">
                    <a class="toggle-menu" href="#">
                        <i></i>
                        <i></i>
                        <i></i>
                    </a>
                </div>
                <div class="mob-user-profile pull-right">
                    <div class="dropdown user-profile">                                
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span> <img src="{{ asset('/images/user-pic-1.png') }}" alt="user" width="40" class="img-profile"></span>
                            <span class="user-name btn-lg">
                                {{ \Auth::user()->name }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-custom">
                            <li>
                                <div class="navbar-content">
                                    <a href="#" class="view btn-lg text-capitalize active">view Profile</a>
                                    <div class="divider">
                                    </div>
                                    <a href="{{ url('logout') }}" class="view btn-lg text-capitalize active">Log out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="search-section pull-left">
                    <div class="search-input" id="input_show">
                        <div class="form-group form-group-lg">
                            <input type="text" name="search" class="form-control form-group-lg log-search"  placeholder="Search..">
                        </div>
                    </div>                    
                    <div class="mobile-search" id="btn_show">                           
                        <button class="btn btn-default btn-search"><i class="fa fa-search"></i></button>
                    </div>

                </div>
                <ul class="list-inline header-top header-link pull-right">
                    <li>
                        <div class="event-section">
                            <div id="reportrange" class="calendar-input">
                                <i class="fa fa-calendar"></i>
                                <span class="date-input"></span> <i class="caret"></i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown dropdown-custom">

                            <button class="btn btn-secondary btn-drop dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="ic-img">
                                    <img src="{{ asset('/images/ic-voice.png') }}" alt="Everyone" width="14">
                                </span> 
                                <span class="current-filter-type">Voice</span>
                                <span class="caret"></span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item btn-lg select-filter-type" data-value="Fax" href="javascript:void(0);">Fax</a>
                                <div class="divider"></div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown dropdown-custom">

                            <button class="btn btn-secondary btn-drop btn-group dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="ic-img"><img src="{{ asset('/images/ic-group.png') }}" alt="Everyone" width="28"></span>  <span>All Group</span> <span class="caret"></span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item btn-lg" href="#">All Group</a>                                      
                                <div class="divider"></div>
                                <a class="dropdown-item btn-lg" href="#">Only me</a>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown dropdown-custom">

                            <button class="btn btn-secondary btn-drop dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="ic-img"><img src="{{ asset('/images/user-pic-white.png') }}" alt="Everyone" width="18"></span>   <span>Everyone</span> <span class="caret"></span>
                            </button>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item btn-lg" href="#">Everyone</a>
                                <div class="divider"></div>
                                <a class="dropdown-item btn-lg" href="#">Only me</a>
                                <div class="divider"></div>
                                <a class="dropdown-item btn-lg" href="#">Custom</a>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown user-profile profile-view">                                
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                @if(!empty(\Auth::user()->image))
                                    <?php $img = asset('uploads/users/'.\Auth::user()->image); ?>
                                @else    
                                    <?php $img = asset('images/default-medium.png'); ?>
                                @endif
                                <img src="{{ $img }}" alt="user" width="40" class="img-profile">
                                <span class="user-name btn-lg">{{ \Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-custom">
                                <li>
                                    <div class="navbar-content">
                                        <a href="{{ route('profile') }}" class="view btn-lg text-capitalize active">view Profile</a>
                                        <div class="divider">
                                        </div>
                                        <a href="{{ url('logout') }}" class="view btn-lg text-capitalize active">Log out</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>                                    
                </ul>
            </div>                    
        </div>
    </div>   
    <div class="menu-drawer">
        <ul  class="nav nav-pills" role="tablist">
            <li  class="nav-item">
                <a  class="nav-link active" data-toggle="pill" href="#menu1">
                    <span class="svg-icon"> 
                        <img src="{{ asset('/images/dashboard.png') }}" width="30">
                    </span>
                    <span class="side-link"> Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#menu2">
                    <span class="svg-icon">
                        <img src="{{ asset('/images/call-log.png') }}" width="30">
                    </span>
                    <span class="side-link"> Call Log</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#menu3">
                    <span class="svg-icon">
                        <img src="{{ asset('/images/ic-location.png') }}" width="30">
                    </span>
                    <span class="side-link"> Location</span>
                </a>
            </li>
        </ul>
    </div>
</header> 
