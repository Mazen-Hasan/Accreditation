<nav class="navbar default-layout  fixed-top d-flex align-items-top flex-row" style="background: transparent">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start"
         style="background: transparent">
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
        <li class="nav-item">
        <form class="search-form" action="#">
        <i class="icon-search"></i>
        <input type="search" class="form-control" placeholder="Search Here" title="Search here">
        </form>
        </li>
        <!-- <li class="nav-item dropdown">
            <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
              <i class="icon-mail icon-lg"></i>
            </a>

          </li> -->
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown"
                   aria-expanded="false">
                    <i>
                        @if (auth()->user()->unreadNotifications->count() == '0')
                            <img src="{{ asset('images/notification-0.png') }}" alt="Notifications" id="notify" onblur="hide()">
                        @else
                            <img src="{{ asset('images/notification.png') }}" alt="Notifications" id="notify">
                        @endif

                    </i>
                    {{--                    <span class="count"></span>--}}
                </a>
                <div id="notify-container" class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                     style="margin-top:-35p;overflow-y: scroll; height:200px;" aria-labelledby="notificationDropdown">
                    <!-- <input type="hidden" id="notifications" value=""> -->
                    <a class="dropdown-item py-3 border-bottom">
                        <p class="mb-0 font-weight-medium float-left">You have
                            <?php echo auth()->user()->unreadNotifications->count() ?>
                            new notifications </p>
                        <!-- <span class="badge badge-pill badge-primary float-right">View all</span> -->
                    </a>
                    <?php foreach (auth()->user()->unreadNotifications as $notification){
                    if($notification->type == "App\Notifications\AlertNotification"){
                    ?>
                    <a href="
                    <?php echo $notification->data["Url"]  ?>
                        " data-id="<?php echo $notification->id ?>" class="dropdown-item preview-item py-3 markAsRead">
                        <div class="preview-thumbnail">
                            <i class="mdi mdi-alert m-auto text-primary"></i>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal text-dark mb-1"><?php echo $notification->data["text"] ?></h6>
                            <p class="fw-light small-text mb-0"> <?php echo $notification->created_at  ?></p>
                        </div>
                    </a>
                <?php
                }
                }?>
                <!-- <a class="dropdown-item preview-item py-3">
                <div class="preview-thumbnail">
                  <i class="mdi mdi-alert m-auto text-primary"></i>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal text-dark mb-1">Application Error</h6>
                  <p class="fw-light small-text mb-0"> Just now </p>
                </div>
              </a>
              <a class="dropdown-item preview-item py-3">
                <div class="preview-thumbnail">
                  <i class="mdi mdi-settings m-auto text-primary"></i>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal text-dark mb-1">Settings</h6>
                  <p class="fw-light small-text mb-0"> Private message </p>
                </div>
              </a>
              <a class="dropdown-item preview-item py-3">
                <div class="preview-thumbnail">
                  <i class="mdi mdi-airballoon m-auto text-primary"></i>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal text-dark mb-1">New user registration</h6>
                  <p class="fw-light small-text mb-0"> 2 days ago </p>
                </div>
              </a> -->
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
<script>
    var selected = 0;

    $('#notify').click(function () {
        if (selected === 0) {
            $('#notify-container').show();
            selected = 1;
        } else {
            $('#notify-container').hide();
            selected = 0;
        }
    });

    $('.markAsRead').click(function () {
        var id = $(this).data("id");
        // alert(id);
        $.ajax({
            type: "get",
            url: "{{ url('markAsRead-notification')}}/" + id,
            success: function (data) {
                // alert($(this).data("url"));
                // window.location.href = $(this).data("url");
                //window.open($(this).data("url"),'_blank');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
        //alert(id);
    });

    function goto() {
        //alert($(this).data("id"));
        var id = $(this).data("id");
        //alert(id);
        $.ajax({
            type: "get",
            url: "{{ url('markAsRead-notification')}}/" + id,
            success: function (data) {
                //alert(data);
                //alert($(this).data("url"));
                //window.location.href = $(this).data("url");
                //window.open($(this).data("url"),'_blank');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

    function getNotifications() {
        var notifyContainer = $('#notify-container');
        $.ajax({
            type: "get",
            url: "{{ url('get-notification')}}",
            success: function (data) {
                var html = '';
                html = html +
                    '<a class="dropdown-item py-3 border-bottom">'
                    + '<p class="mb-0 font-weight-medium float-left">You have '
                    + data.length
                    + ' new notifications </p>'
                    + '</a>';
                if (data.length > 0) {
                    var i = 0;
                    while (i < data.length) {
                        html = html +
                            '<a href="'
                            + data[i].data["Url"]
                            + '" data-id="'
                            + data[i].id
                            + '" class="dropdown-item preview-item py-3 markAsRead" onclick="goto.call(this)">'
                            + '<div class="preview-thumbnail">'
                            + '<i class="mdi mdi-alert m-auto text-primary"></i>'
                            + '</div>'
                            + '<div class="preview-item-content">'
                            + '<h6 class="preview-subject fw-normal text-dark mb-1">'
                            //+ data[i].data["event_name"] + ': ' + data[i].data["company_name"] +': ' + data[i].data["action"]
                            + data[i].data["text"]
                            + '</h6>'
                            + '<p class="fw-light small-text mb-0">'
                            + data[i].created_at.split('.')[0].replace('T', ' ')
                            + '</p>'
                            + '</div>'
                            + '</a>';
                        i++;
                    }
                }
                if (html != '') {
                    console.log(html);
                    notifyContainer.html(html);
                }

            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

    window.setInterval(function () {
        getNotifications()
    }, 10000);

    $(document).mouseup(function(e) 
{
    var container = $("#notify-container");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        container.hide();
        selected = 0;
    }
});
</script>
