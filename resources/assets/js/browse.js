var BrowseGalleries = function() {

    //set csrf token for ajax call
    var ajaxCSRFToken = function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }



    //data table section
    var DataTable = function() {


        var obj = $('#browse_galleries');
        if (obj.length === 0) return false;
        // Setting datatable defaults


        var dt = $('#browse_galleries').DataTable({
            // dom: "<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'Bi><'col-sm-12 col-md-7 dataTables_pager'pl>>",
            dom: '<"datatable-header"B><"datatable-scroll"t><"datatable-footer"ilp>',
            bAutoWidth:false,
            // language: {
            //
            //     paginate: {
            //         'first': 'First',
            //         'last': 'Last',
            //         'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
            //         'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
            //     }
            // },
            "processing": true,
            "serverSide": true,
            "searchDelay": 500,
            // Set rows IDs
            rowId: function(a) {
                return 'id_' + a.id;
            },
            "language": language,
            "pageLength": implicit_nr_listing,
            "lengthMenu": pages_range,
            "order": [
                [0, 'desc']
            ],
            "ajax": {
                "url": getAdmins_route,
                "type": "POST",

            },
            orderCellsTop: true,
            fixedHeader: true,
            "columns": [{
                "data": "id",
                "orderable": true,
                "searchable": false,
                "sortable": false,

                // render: function(data, type, row) {
                //     return ''
                // }
            },
                {
                    "data": "name",
                    "visible": true,
                    "searchable": true,
                    "orderable": true,
                },
                {
                    "data": "active",
                    "visible": true,
                    "searchable": true,
                    "orderable": true,
                    "class": "text-center"

                },

                {
                    "data": "action",
                    "visible": true,
                    "searchable": false,
                    "orderable": false,
                    "class": "text-center",
                },
            ],

        });

        dt.buttons().container().appendTo('#buttons');

        $('#browse_galleries').on('click', '.delete', function() {
            var d = $(this);
            d.closest('tr').find('.checkable-row').attr('checked', true);
            $("#delete_modal").modal('show');
        });
        $("#check_all").on("click", function(){
            if ($(this).is(":checked"))
                $("#delete_selected").show();
            else
                $("#delete_selected").hide();

            $('input:checkbox.checkable-row').prop('checked', this.checked);
        });

        $('#browse_galleries').on('click', '.checkable-row', function () {
            if ($(this).prop('checked')) {
                $('#delete_selected').show();
                $('#check_all').prop('checked', true);
            }
            else {
                var count = $('.checkable-row:checkbox:checked').length;

                if (count == 0) {
                    $('#delete_selected').hide();
                    $('#check_all').prop('checked', false);
                }
            }
        });

        //delete roles
        $(".modalDeleteButton").on("click", function() {
            //$.blockUI({ message: '<h1><img src="busy.gif" /> Just a moment...</h1>' });
            deleteGallery();
            $("#delete_modal").modal('hide');
        });
        $(".cancel_delete").on('click', function() {
            $(".checkable-row").prop("checked", false);
            $('#check_all').prop('checked', false);
            $('#delete_selected').hide();
        });

        $("#delete_selected").on('click',function(){
            $("#delete_modal").modal('show');
        });


        var deleteGallery = function(id) {
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

                        dt.ajax.reload();

                        $('#delete_selected').hide();
                        $('#check_all').prop('checked', false);

                        // Styled right
                        new PNotify({
                            // title: 'Right icon',
                            text: errorsHtml,
                            addclass: class_pnotify,
                            type: alert_type
                        });
                    }
                }
            });
        }


        return dt;

    }

    return {
        // public functions
        init: function() {
            ajaxCSRFToken();
            _dataTableExtensions();
            var dt = DataTable();
            _addDataTableColumnFilter(dt);
            _componentSelect2();



        }
    };
}()
jQuery(document).ready(function() {
    BrowseGalleries.init()
});
