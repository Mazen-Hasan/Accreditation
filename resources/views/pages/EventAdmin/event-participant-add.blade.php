@extends('main')
@section('subtitle',' Participants')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
	<div class="modal fade" id="loader-modal" tabindex="-1" data-backdrop="static" data-keyboard="false"
         role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 250px">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <div class="col-sm-10">
                            <label class="loading">
                                loading...
                            </label>
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
                    $('#btn-save').html('Sending..');
					$('#loader-modal').modal('show');
                	$('#btn-save').prop('disabled', true);
                    $.ajax({
                        data: $('#templateForm').serialize(),
                        url: "{{ route('eventStoreParticipant') }}",

                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            // $('#templateForm').trigger("reset");
                            // $('#template-modal').modal('hide');
                            $('#btn-save').html('Done');
                            // var oTable = $('#laravel_datatable').dataTable();
                            // oTable.fnDraw(false);
                        	$('#loader-modal').modal('hide');
                            window.location.href = "{{ route('eventCompanyParticipants',[$companyId,$eventId])}}";
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        	$('#loader-modal').modal('hide');
                            $('#btn-save').html('Done');
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
