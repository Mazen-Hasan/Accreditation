
<nav class="navbar default-layout  fixed-top d-flex align-items-top flex-row" style="background: transparent">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start" style="background: transparent">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
{{--        <div>--}}
{{--            <a class="navbar-brand brand-logo-mini" href="index.html">--}}
{{--                <img src="{{ URL::asset('images/logo-mini.svg')}}" alt="logo" />--}}
{{--            </a>--}}
{{--        </div>--}}
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top" style="background: transparent">
        <ul class="navbar-nav ms-auto">
{{--            <li class="nav-item">--}}
{{--                <form class="search-form" action="#">--}}
{{--                    <i class="icon-search"></i>--}}
{{--                    <input type="search" class="form-control" placeholder="Search Here" title="Search here">--}}
{{--                </form>--}}
{{--            </li>--}}
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i>
                        <img src="{{ asset('images/notification.png') }}" alt="Notifications">
                    </i>
{{--                    <span class="count"></span>--}}
                </a>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
