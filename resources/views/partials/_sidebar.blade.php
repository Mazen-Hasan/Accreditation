<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <ul>
            <li class="nav-item">
                <span class="nav-link">
                    <p class="user_name">
                       {{ Auth::user()->name }}
                   </p>
                </span>
                <span class="nav-link">
                   <p class="user_role">
                       {{ Auth::user()->roles->first()->name }}
                   </p>
                   <br>
                </span>
            </li>
        </ul>
        @role('super-admin')
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'event') =="1" ? "active" : "" }}"
               href="{{ route('events') }} ">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Events">
                </i>
                <span class="menu-title">Event Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'companyCategories') =="1" ? "active" : "" }}"
               href="{{ route('companyCategories') }}">
                <i class="logout">
                    <img src="{{ asset('images/company.png') }}" alt="Company Categories">
                </i>
                <span class="menu-title">Company Categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'securityCategories') =="1" ? "active" : "" }}"
               href="{{ route('securityCategories') }}">
                <i class="logout">
                    <img src="{{ asset('images/security.png') }}" alt="Security Category">
                </i>
                <span class="menu-title">Security Categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'eventType') =="1" ? "active" : "" }}"
               href="{{ route('eventTypes') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Event Type">
                </i>
                <span class="menu-title">Event Types</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'templates') =="1" ? "active" : "" }}"
               href="{{ route('templates') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Registration Forms">
                </i>
                <span class="menu-title">Registration Forms</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'adges') =="1" ? "active" : "" }}"
               href="{{ route('templateBadge') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Badges">
                </i>
                <span class="menu-title">Badges</span>
            </a>
        </li>
    	<li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'email') =="1" ? "active" : "" }}"
               href="{{ route('emailTemplates') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Email Templates">
                </i>
                <span class="menu-title">Email Templates</span>
            </a>
        </li>
        {{--        <li class="nav-item">--}}
        {{--            <a class="nav-link {{ str_contains( Request::route()->getName(),'companies') =="1" ? "active" : "" }}"--}}
        {{--               href="{{ route('companies') }}">--}}
        {{--                <i class="logout">--}}
        {{--                    <img src="{{ asset('images/company.png') }}" alt="Companies">--}}
        {{--                </i>--}}
        {{--                <span class="menu-title">Companies</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'accreditationCategories') =="1" ? "active" : "" }}"
               href="{{ route('accreditationCategories') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Accreditation Categories">
                </i>
                <span class="menu-title">Accreditation Category</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'contact') =="1" ? "active" : "" }}"
               href="{{ route('contacts') }}">
                <i class="logout">
                    <img src="{{ asset('images/participant.png') }}" alt="Contact">
                </i>
                <span class="menu-title">Event Organizer</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'users') =="1" ? "active" : "" }}"
               href="{{ route('users') }}">
                <i class="logout">
                    <img src="{{ asset('images/user_mng.png') }}" alt="Users">
                </i>
                <span class="menu-title">Users</span>
            </a>
        </li>
        @endrole
        @role('event-admin')
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'event-admin') =="1" ? "active" : "" }}"
               href="{{ route('event-admin') }} ">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="My events">
                </i>
                <span class="menu-title">Events</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'focalpoints') =="1" ? "active" : "" }}"
               href="{{ route('focalpoints') }}">
                <i class="logout">
                    <img src="{{ asset('images/user_mng.png') }}" alt="Focal Points">
                </i>
                <span class="menu-title">Focal Points</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'fullFillment') =="1" ? "active" : "" }}"
               href="{{ route('Selections')}}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Users">
                </i>
                <span class="menu-title">Fulfillment</span>
            </a>
        </li>
        @endrole
        @role('company-admin')
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'company-admin') =="1" ? "active" : "" }}"
               href="{{ route('company-admin') }} ">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="My events">
                </i>
                <span class="menu-title">Events</span>
            </a>
        </li>
        @yield('custom_navbar')
        @endrole
        @role('security-officer')
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'security-officer-admin') =="1" ? "active" : "" }}"
               href="{{ route('security-officer-admin') }} ">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="My events">
                </i>
                <span class="menu-title">Events</span>
            </a>
        </li>
        @endrole
        @role('data-entry')
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'data-entry') =="1" ? "active" : "" }}"
               href="{{ route('dataEntryEvents') }} ">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="My events">
                </i>
                <span class="menu-title">Events</span>
            </a>
        </li>
        @endrole
        <br>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                <i class="logout">
                    <img src="{{ asset('images/log-out.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>
