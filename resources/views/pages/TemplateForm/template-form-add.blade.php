@extends('main')
@section('subtitle',' Templates')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <br><br>
        <div class="row">
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
@endsection
@section('script')
    <script>
        $(document).ready( function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        if ($("#templateForm").length > 0) {
            $("#templateForm").validate({
                submitHandler: function(form) {
                    $('#btn-save').html('Sending..');

                    $.ajax({
                        data: $('#templateForm').serialize(),
                        url: "{{ route('templateFormController.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            window.location.href = "{{ route('companyParticipants')}}";
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }

    $('.img-upload').submit(function(e) {
            var btnID = this.id;
            btnID =  btnID.substring(5,btnID.length -1);
            var btn_upl = '#btn-upload_' + btnID;

            $(btn_upl).html('Sending..');

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

                type:'POST',
                url: "{{ url('upload-file')}}",
                data: formData,
                cache:false,
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

                    var bg_image = '#bg_image_' + btnID;
                    $(bg_image).val(data.fileName);

                    var btnID = this.id;
                    btnID =  btnID.substring(5,btnID.length -1);
                    btnID = "#" + btnID;
                    $(btnID).val(data.fileName);
                },

                error: function(data){
                    $("#file_type_error").html('Error uploading file');
                }
            });
        });
    </script>
@endsection
