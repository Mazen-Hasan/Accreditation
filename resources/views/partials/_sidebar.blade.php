<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <ul>
                <li>
                    <p class="user_name">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="user_role">
                        {{ Auth::user()->roles->first()->name }}
                    </p>
                </li>
            </ul>
        </li>
    </ul>
    <ul class="nav">
        @role('super-admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('events') }} ">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Events</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('contacts') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Contacts</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('titles') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Titles</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('companyCategories') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Company categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('securityCategories') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Security categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('eventTypes') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Event types</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('companies') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Companies</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('accreditationCategories') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Accreditation Category management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('participants') }}">
                <i class="logout">
                    <img src="{{ asset('images/menu.png') }}" alt="Logout">
                </i>
                <span class="menu-title">Participant management</span>
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
