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
                    <img src="{{ asset('images/menu.png') }}" alt="Company Categories">
                </i>
                <span class="menu-title">Company categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'securityCategories') =="1" ? "active" : "" }}"
               href="{{ route('securityCategories') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Security Category">
                </i>
                <span class="menu-title">Security categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'eventType') =="1" ? "active" : "" }}"
               href="{{ route('eventTypes') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Event Type">
                </i>
                <span class="menu-title">Event types</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'templates') =="1" ? "active" : "" }}"
               href="{{ route('templates') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Templates">
                </i>
                <span class="menu-title">Templates</span>
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
                    <img src="{{ asset('images/menu.png') }}" alt="Contact">
                </i>
                <span class="menu-title">Event Organizer/Owner</span>
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
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'fullFillment') =="1" ? "active" : "" }}"
               href="{{ route('Selections')}}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Users">
                </i>
                <span class="menu-title">FullFillment</span>
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
                    <img src="{{ asset('images/menu.png') }}" alt="Focal Points">
                </i>
                <span class="menu-title">Focal Points</span>
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
        <li id="subsidiaries_nav" class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'subCompanies') =="1" ? "active" : "" }}"
               href="{{ route('subCompanies') }} ">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="My Sidries">
                </i>
                <span class="menu-title">Subsidiaries</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_contains( Request::route()->getName(),'dataentrys') =="1" ? "active" : "" }}"
               href="{{ route('dataentrys') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Data Entry">
                </i>
                <span class="menu-title">Data Entry</span>
            </a>
        </li>
        {{--        <li id="subsidiaries_nav" class="nav-item">--}}
        {{--            <a class="nav-link {{ str_contains( Request::route()->getName(),'subCompanies') =="1" ? "active" : "" }}"--}}
        {{--               href="{{ route('subCompanies') }} ">--}}
        {{--                <i class="logout">--}}
        {{--                    <img src="{{ asset('images/menu.png') }}" alt="My Sidries">--}}
        {{--                </i>--}}
        {{--                <span class="menu-title">Subsidiaries</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        {{--        <li class="nav-item">--}}
        {{--            <a class="nav-link {{ str_contains( Request::route()->getName(),'dataentrys') =="1" ? "active" : "" }}"--}}
        {{--               href="{{ route('dataentrys') }}">--}}
        {{--                <i class="logout">--}}
        {{--                    <img src="{{ asset('images/menu.png') }}" alt="Data Entry">--}}
        {{--                </i>--}}
        {{--                <span class="menu-title">Data Entry</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
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
