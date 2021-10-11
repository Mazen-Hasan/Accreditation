<!DOCTYPE html>
<html lang="en">
@include('partials._head')
@yield('style')
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
@include('partials._navbar')
<!-- partial -->
    <div class="container-fluid page-body-wrapper" style="padding-right: 0px;padding-left: 0px">
        <!-- partial:partials/_settings-panel.html -->
    {{--        @include('partials._setting-panel')--}}
    <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
    @include('partials._sidebar')
    <!-- partial -->
        <div class="main-panel">
        @yield('content')
        <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
        @include('partials._footer')
        <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
@yield('script')
<!-- container-scroller -->

<!-- plugins:js -->

<!-- End custom js for this page-->
</body>

</html>

