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
                        <div class="row align-content-md-center" style="height: 80px">
                            <div class="col-md-10">
                                <p class="card-title">Participant - New</p>
                            </div>
                            <div class="col-md-2 align-content-md-center">
                                <a href="javascript:void(0)" id="import" class="add-hbtn">
                                    <i>
                                        <img src="{{ asset('images/add.png') }}" alt="Add">
                                    </i>
                                    <span class="dt-hbtn">Import</span>
                                </a>
                            </div>
                        </div>
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
	<div class="modal" id="loader-modal" tabindex="-1" data-backdrop="static" data-keyboard="false"
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

    <div class="modal fade" id="import-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="importForm" name="importForm" class="form-horizontal">
                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Full Name</label>
                            <div class="col-sm-12">
                                <input type="text" id="participantFullName" name="participantFullName" minlength="5"
                                       maxlength="50"
                                       placeholder="enter Full Name" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Participants</label>
                            <div class="col-sm-12">
                                <select id="participants" name="participants">
                                </select>
                            </div>
                            <label id="import_error" class="error"></label>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4">
                                <button type="button" class="btn-cancel" data-dismiss="modal" id="btn-cancel">Cancel
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" data-dismiss="modal" id="btn-import">Import</button>
                            </div>
                        </div>
                    </form>
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

        $('#import').click(function () {
            $('#fullName').val('');
            $('#importForm').trigger("reset");
            $('#participants').find('option[value]').remove();
            $('#modalTitle').html("Import Participant");
            $("#import_error").html('');
            $('#import-modal').modal('show');
        });

        $('#participantFullName').keyup(function () {
            $('#participants').find('option[value]').remove();
            let companyID = $("#company_id").val();
            let fullName = $("#participantFullName").val();

            if (fullName.length > 4) {
                let url = "{{ route('searchParticipants', [':fullName',':companyId']) }}";
                url = url.replace(':fullName', fullName);
                url = url.replace(':companyId', companyID);
                $.ajax({
                    type: "get",
                    url: url,
                    success: function (data) {
                        participantsData = data.list;
                        $("#import_error").html('');
                        buildParticipantsList(data.searchRes);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
            else {
                $("#import_error").html('');
            }
        });

        function buildParticipantsList(data) {
            if (data.length === 0) {
                $("#import_error").html('No participants was found');
            } else {
                $("#import_error").html('');
                $.each(data, function (key, value) {
                    $('#participants').append($('<option>', {
                        value: value['staff_id'],
                        text: value['value']
                    }));
                });
            }
        }

        function fillForm(participant_id) {
            var formFields = '';

            $.each($(':input:not([type=hidden],[type=submit],[type=file])', '#templateForm'), function (k) {
                formFields += 'Id: ' + $(this).attr('id') + ', Name: ' + $(this).attr('name') + ', Value: ' + $(this).val() + ', Type: ' + $(this).attr('type') + '\n';
                fillFormField($(this).attr('id'), participant_id);
            });
        }

        function fillFormField(field_id, participant_id) {
            $('#' + field_id).val(participantsData[participant_id][field_id]);
        }

        $('#import-modal button').on('click', function (event) {
            var $button = $(event.target);

            $(this).closest('.modal').one('hidden.bs.modal', function () {
                if ($button[0].id === 'btn-import') {
                    var participant_id = $('#participants').find('option:selected').val();
                    if (!participant_id) {
                        $("#import_error").html('No participants found');
                    } else {
                        fillForm(participant_id);
                    }
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
                            $('#btn-save').html('Done');
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
                    $('#btn-save').prop('disabled', true);
                },

                success: (data) => {
                    this.reset();
                    var file_type_error = '#file_type_error_' + btnID;
                    $(file_type_error).html('File uploaded successfully');

                    $(btn_upl).html('Upload');

                    var bg_image = '#bg_image_' + btnID;
                    $(bg_image).val(data.fileName);

                    var btnID = this.id;
                    btnID = btnID.substring(5, btnID.length - 1);
                    btnID = "#" + btnID;
                    $(btnID).val(data.fileName);
                    $('#btn-save').prop('disabled', false);
                },

                error: function (data) {
                    $("#file_type_error").html('Error uploading file');
                    console.log(data);
                }
            });
        });
    </script>
@endsection
