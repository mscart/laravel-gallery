@extends('admin.app')
@section('page_title') @lang('galleries::gallery.list') @endsection
@section('pagetitle') @lang('galleries::gallery.list') @endsection
@section('breadcrumb')
    <span class="breadcrumb-item active">@lang('galleries::gallery.list')</span>
@endsection
@section('content')
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">@lang('galleries::gallery.list')</h5>
            <div class="header-elements">
                <div class="list-icons-item">
                    @can('galleries::gallery.role.delete')
                        <button class="btn bg-danger " style="display:none;"  id="delete_selected"><span><i class="icon-trash"></i> @lang('admin/general.actions.delete_selected')</span></button>
                    @endcan
                </div>
                <div class="list-icons list-icons-item" id="buttons"></div>

            </div>
        </div>
        <table id="browse_galleries" class="table data_table">
            <thead>
            <tr>
                <th></th>
                <th >@lang('galleries::gallery.gallery_name')</th>
                <th >@lang('galleries::gallery.active')</th>

                <th class="text-center">@lang('admin/general.td_actions')</th>
            </tr>
            <tr>
                <th width="1%">
                    <input type="checkbox" class="check_all " id="check_all"/>
                </th>
                <th width="70%" class="searchable "></th>
                <th width="10%" class="searchable "></th>

                <th width="5%" ></th>

            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @include('admin.partials.modal_delete')
@endsection
@section('custom_js')
    @include('admin.partials.data_table_vars')
    <script src="{{ asset('backend/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/mscart/galleries/assets/js/browse.js') }}"></script>
    <script>
        var report_title = 'Galleries report';
        var ajax_url = "{{ route('galleries.destroy','delete')}}";
        var getAdmins_route = " {{ route('galleries.getGalleries') }}"

    </script>
@endsection
