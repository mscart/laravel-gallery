var Plupload = function() {


    var _moveImages = function()
    {
        $("#move_selected").on('click',function(){
            $("#move_modal").modal('show');
            var ids = [];
            $('.checkable-row:checked').each(function() {
                ids.push($(this).val());
            });
            $("#images_id").val(ids);
        });
    }
    var _deleteImage = function()
    {


        $('#browse_images').on('click', '.delete', function() {
            var d = $(this);
            d.closest('div').find('.checkable-row').prop('checked', true).uniform('refresh');

            $("#delete_modal").modal('show');
        });
        $("#check_all").on("click", function(){
            if ($(this).is(":checked"))
            {
                $("#delete_selected").show();
                $("#move_selected").show();
            }
            else {
                $("#delete_selected").hide();
                $("#move_selected").hide();
            }
            $('input:checkbox.checkable-row').prop('checked', this.checked).uniform('refresh');
        });

        $('#browse_images').on('click', '.checkable-row', function () {
            if ($(this).prop('checked')) {
                $('#delete_selected').show();
                $("#move_selected").show();
                $('#check_all').prop('checked', true);
            }
            else {
                var count = $('.checkable-row:checkbox:checked').length;

                if (count == 0) {
                    $('#delete_selected').hide();
                    $("#move_selected").hide();
                    $('#check_all').prop('checked', false);
                }
            }
        });

        //delete roles
        $(".modalDeleteButton").on("click", function() {
            //$.blockUI({ message: '<h1><img src="busy.gif" /> Just a moment...</h1>' });
            deleteImages();
            $("#delete_modal").modal('hide');
        });
        $(".cancel_delete").on('click', function() {
            $(".checkable-row").prop("checked", false).uniform('refresh');
            $('#check_all').prop('checked', false).uniform('refresh');
            $('#delete_selected').hide();
            $("#move_selected").hide();
        });

        $("#delete_selected").on('click',function(){
            $("#delete_modal").modal('show');
        });

        var deleteImages = function(id) {
            var ids = [];

            $('.checkable-row:checked').each(function() {
                ids.push($(this).val());
            });
            ajax_data = "ids=" + ids + '&_method=DELETE';


            $.ajax({
                type: "POST",
                url: ajax_url,
                dataType: 'json',
                data: ajax_data,
                success: function(response) {
                    if (response.success === true) {

                        var errorsHtml = '';
                        $.each(response.messages, function(index, value) {
                            errorsHtml += '<p>' + value + '</p>';
                        });
                        alert_type = 'success';
                        class_pnotify = 'bg-success border-success';
                        $.each(ids, function(index, val) {
                            var check_obj = $('#browse_admins tr#id_' + val);
                            if (response.ids_error && $.inArray(val, response.ids_error) !== -1) {
                                alert_type = 'error';
                                class_pnotify = 'bg-danger border-danger';
                            } else {
                                check_obj.remove();
                            }
                        });



                        $('#delete_selected').hide();
                        $('#check_all').prop('checked', false);

                        // Styled right
                        new PNotify({
                            // title: 'Right icon',
                            text: errorsHtml,
                            addclass: class_pnotify,
                            type: alert_type
                        });

                        location.reload();
                    }
                }
            });
        }
    }

    // Bootstrap file upload
    var _componentPlupload = function() {
        if (!$().pluploadQueue) {
            console.warn('Warning - Plupload files are not loaded.');
            return;
        }

        // Setup all runtimes
        $('.file-uploader').pluploadQueue({
            runtimes: 'html5, html4, Flash, Silverlight',
            url: upload_url,
            chunk_size: settings_chuck_size,
            max_file_size: settings_max_file_size,
            unique_names: false,
            header: true,
            dragdrop: true,
            // add X-CSRF-TOKEN in headers attribute to fix this issue
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            // Specify what files to browse for
            filters : [
                {title : "Image files", extensions : settings_allowed_extensions},
                // {title : "Zip files", extensions : "zip,avi,mpg,mp4"}
            ],
            // Flash settings
            flash_swf_url : '/backend/js/plugins/uploaders/plupload/files/Moxie.swf',
            // Silverlight settings
            silverlight_xap_url : '/backend/js/plugins/uploaders/plupload/files/Moxie.xap',
            init: {
                BeforeUpload: function(up, file) {
                    //log('[BeforeUpload]', 'File: ', file);
                },
                UploadComplete: function(up, file, info) {
                    location.reload();
                },
                ChunkUploaded: function (up, file, info)
                {
                    var response = jQuery.parseJSON(info.response);
                    console.log(response);
                    if (response !== null) {
                        if (response.error) {
                            up.stop();
                            file.status = plupload.FAILED;
                            up.trigger('QueueChanged');            //Line A
                            up.trigger('UploadProgress', file);     // Line B
                            up.start();

                            // Styled right
                            new PNotify({
                                // title: 'Right icon',
                                text: response.error.message,
                                addclass: 'bg-danger border-danger',
                                type: 'erro'
                            });
                        }
                    }
                },

            }
        });


        // Write log
        function log() {
            var str = '';

            plupload.each(arguments, function(arg) {
                var row = '';

                if (typeof(arg) != 'string') {
                    plupload.each(arg, function(value, key) {

                        // Convert items in File objects to human readable form
                        if (arg instanceof plupload.File) {

                            // Convert status to human readable
                            switch (value) {
                                case plupload.QUEUED:
                                    value = 'QUEUED';
                                    break;

                                case plupload.UPLOADING:
                                    value = 'UPLOADING';
                                    break;

                                case plupload.FAILED:
                                    value = 'FAILED';
                                    break;

                                case plupload.DONE:
                                    value = 'DONE';
                                    break;
                            }
                        }

                        if (typeof(value) != 'function') {
                            row += (row ? ', ': '') + key + '=' + value;
                        }
                    });

                    str += row + ' ';
                }
                else {
                    str += arg + ' ';
                }
            });

            var log = $('#log');
            log.append(str + '<br>');
            log.scrollTop(log[0].scrollHeight);
        }
    }

    return {
        init: function() {
            ajaxCSRFToken();
            _multiValidate();
            _componentSelect2();
            _componentPlupload();
            _componentFancybox();
            _componentUniform();
            _deleteImage();
            _moveImages();

        }
    }

}();

   // Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    Plupload.init();
    $('.video').on('click',function () {

        this.paused?this.play():this.pause();
    });
});
