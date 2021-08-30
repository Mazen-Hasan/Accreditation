<nav class="sidebar sidebar-offcanvas" id="sidebar" style="background: transparent">
    <ul class="nav">
        <li class="nav-item">
{{--                {{ Auth::user()->name }}--}}
{{--                <br>--}}
{{--                {{ Auth::user()->roles->first()->name }}--}}
        </li>
    </ul>
            <ul class="nav">
                @role('event-admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('events') }} ">
                        <i class="mdi mdi-grid-large menu-icon"></i>
                        <span class="menu-title">Event management</span>
                    </a>
                </li>
                @endrole
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contacts') }}">
                        <i class="mdi mdi-grid-large menu-icon"></i>
                        <span class="menu-title">Contact management</span>
                    </a>
                </li>
                @role('super-admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('titles') }}">
                        <i class="mdi mdi-grid-large menu-icon"></i>
                        <span class="menu-title">Title management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('companyCategories') }}">
                        <i class="mdi mdi-grid-large menu-icon"></i>
                        <span class="menu-title">Company category management</span>
                    </a>
                </li>
                @endrole
{{--                <li class="nav-item nav-category">UI Elements</li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">--}}
{{--                        <i class="menu-icon mdi mdi-floor-plan"></i>--}}
{{--                        <span class="menu-title">UI Elements</span>--}}
{{--                        <i class="menu-arrow"></i>--}}
{{--                    </a>--}}
{{--                    <div class="collapse" id="ui-basic">--}}
{{--                        <ul class="nav flex-column sub-menu">--}}
{{--                            <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>--}}
{{--                            <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Dropdowns</a></li>--}}
{{--                            <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--                <li class="nav-item nav-category">Forms and Datas</li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">--}}
{{--                        <i class="menu-icon mdi mdi-card-text-outline"></i>--}}
{{--                        <span class="menu-title">Form elements</span>--}}
{{--                        <i class="menu-arrow"></i>--}}
{{--                    </a>--}}
{{--                    <div class="collapse" id="form-elements">--}}
{{--                        <ul class="nav flex-column sub-menu">--}}
{{--                            <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">--}}
{{--                        <i class="menu-icon mdi mdi-chart-line"></i>--}}
{{--                        <span class="menu-title">Charts</span>--}}
{{--                        <i class="menu-arrow"></i>--}}
{{--                    </a>--}}
{{--                    <div class="collapse" id="charts">--}}
{{--                        <ul class="nav flex-column sub-menu">--}}
{{--                            <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">--}}
{{--                        <i class="menu-icon mdi mdi-table"></i>--}}
{{--                        <span class="menu-title">Tables</span>--}}
{{--                        <i class="menu-arrow"></i>--}}
{{--                    </a>--}}
{{--                    <div class="collapse" id="tables">--}}
{{--                        <ul class="nav flex-column sub-menu">--}}
{{--                            <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">--}}
{{--                        <i class="menu-icon mdi mdi-layers-outline"></i>--}}
{{--                        <span class="menu-title">Icons</span>--}}
{{--                        <i class="menu-arrow"></i>--}}
{{--                    </a>--}}
{{--                    <div class="collapse" id="icons">--}}
{{--                        <ul class="nav flex-column sub-menu">--}}
{{--                            <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--                <li class="nav-item nav-category">pages</li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">--}}
{{--                        <i class="menu-icon mdi mdi-account-circle-outline"></i>--}}
{{--                        <span class="menu-title">User Pages</span>--}}
{{--                        <i class="menu-arrow"></i>--}}
{{--                    </a>--}}
{{--                    <div class="collapse" id="auth">--}}
{{--                        <ul class="nav flex-column sub-menu">--}}
{{--                            <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
            </ul>

                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

        </nav>
