@extends('main')
@section('subtitle',' Templates')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('custom_navbar')
            @if($subCompany_nav == 1)
                <li id="subsidiaries_nav" class="nav-item">
                     <a class="nav-link {{ str_contains( Request::route()->getName(),'subCompanies') =="1" ? "active" : "" }}"
                        href="{{ route('subCompanies',[$companyId,$eventId]) }} ">
                         <i class="logout">
                             <img src="{{ asset('images/menu.png') }}" alt="My Sidries">
                         </i>
                         <span class="menu-title">Subsidiaries</span>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link {{ str_contains( Request::route()->getName(),'dataentrys') =="1" ? "active" : "" }}"
                        href="{{ route('dataentrys',[$companyId,$eventId]) }}">
                         <i class="logout">
                             <img src="{{ asset('images/menu.png') }}" alt="Data Entry">
                         </i>
                         <span class="menu-title">Data Entry</span>
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
                @endif
@endsection
@section('content')
    <div class="content-wrapper">
        <br><br>
        <div class="row">
            <input type="hidden" id="subCompnay_status" value={{$subCompany_nav}} />
            <div class="col-12 grid-margin">
                <div class="card" style="border-radius: 20px">
                    <div class="card-body">
                        <h4 class="card-title">Participant - New</h4>
                        <form class="form-sample" id="templateForm" name="templateForm">
                        <!-- <input type="hidden" id="company_id" value={{$companyId}} />
                        <input type="hidden" id="event_id" value={{$eventId}} /> -->
                            <?php echo $form ?>
                        </form>
                        <br>
                        <?php echo $attachmentForm ?>
                        <div class="col-sm-offset-2 col-sm-2">
                            <button type="submit" id="btn-save" value="create">Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            var subCompany_status = $('#subCompnay_status').val();
            if (subCompany_status == 0) {
                $('#subsidiaries_nav').hide();
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        if ($("#templateForm").length > 0) {
            $("#templateForm").validate({
                submitHandler: function (form) {
                    var compnayId = $('#company_id').val();
                    $('#btn-save').html('Sending..');
                    $.ajax({
                        data: $('#templateForm').serialize(),
                        url: "{{ route('templateFormController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            // $('#templateForm').trigger("reset");
                            // $('#template-modal').modal('hide');
                            // $('#btn-save').html('Save Changes');
                            // var oTable = $('#laravel_datatable').dataTable();
                            // oTable.fnDraw(false);
                            window.location.href = "{{ route('companyParticipants',[$companyId,$eventId])}}";
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }

        $('.img-upload').submit(function (e) {
            var btnID = this.id;
            btnID = btnID.substring(5, btnID.length - 1);
            var btn_upl = '#btn-upload_' + btnID;

            $(btn_upl).html('Sending..');

            // $('#btn-upload').html('Sending..');
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('template_id', $('#h_template_id').val());
            $.ajax({
                xhr: function () {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (element) {
                        if (element.lengthComputable) {
                            var percentComplete = ((element.loaded / element.total) * 100);

                            var file_progress_bar = '#file-progress-bar_' + btnID;

                            $(file_progress_bar).width(percentComplete + '%');
                            $(file_progress_bar).html(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },

                type: 'POST',
                url: "{{ url('upload-file')}}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,

                beforeSend: function () {
                    var file_progress_bar = '#file-progress-bar_' + btnID;
                    $(file_progress_bar).width('0%');
                },

                success: (data) => {
                    this.reset();
                    var file_type_error = '#file_type_error_' + btnID;
                    $(file_type_error).html('File uploaded successfully');

                    $(btn_upl).html('Upload');
                    // $('#btn-upload').html('Upload');

                    var bg_image = '#bg_image_' + btnID;
                    $(bg_image).val(data.fileName);

                    // $("#bg_image").val(data.fileName);

                    var btnID = this.id;
                    btnID = btnID.substring(5, btnID.length - 1);
                    btnID = "#" + btnID;
                    $(btnID).val(data.fileName);
                    console.log(data);

                },

                error: function (data) {
                    $("#file_type_error").html('Error uploading file');
                    console.log(data);
                }
            });
        });
    </script>
@endsection
