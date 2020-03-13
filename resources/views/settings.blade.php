@extends('admin.app')
@section('page_title') @lang('galleries::gallery.settings') @endsection
@section('pagetitle') @lang('galleries::gallery.settings') @endsection
@section('breadcrumb')
    <span class="breadcrumb-item active">@lang('galleries::gallery.settings')</span>
@endsection
@section('content')
<form action="{{route('galleries.saveSettings')}}" method="post" class="validate">
    {{ csrf_field() }}
    <div class="card">
        <div class="card-header bg-transparent header-elements-inline">
            <span class="text-uppercase font-size-sm font-weight-semibold">@lang('galleries::gallery.settings')</span>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#general" class="nav-link active" data-toggle="tab">@lang('galleries::gallery.setting.general')</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="general">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">@lang('galleries::gallery.setting.chuck_size')</label>
                                <input type="text" class="form-control" name="settings_chuck_size" value="{{(isset($settings['settings_chuck_size']))?$settings['settings_chuck_size']:''}}" required />
                                <span class="form-text text-muted">@lang('galleries::gallery.setting.chuck_size_helper')</span>
                            </div>
                            <div class="form-group">
                                <label for="">@lang('galleries::gallery.setting.max_file_size')</label>
                                <input type="text" class="form-control" name="settings_max_file_size" value="{{(isset($settings['settings_max_file_size']))?$settings['settings_max_file_size']:''}}" required />
                                <span class="form-text text-muted">@lang('galleries::gallery.setting.max_file_size_helper')</span>
                            </div>
                            <div class="form-group">
                                <label for="">@lang('galleries::gallery.setting.allowed_extensions')</label>
                                <input type="text" class="form-control" name="settings_allowed_extensions" value="{{(isset($settings['settings_allowed_extensions']))?$settings['settings_allowed_extensions']:''}}" required />
                                <span class="form-text text-muted">@lang('galleries::gallery.setting.allowed_extensions_helper')</span>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">@lang('admin/general.submit')</button>
        </div>

    </div>

</form>
@endsection
@section('custom_js')
    <script src="{{ asset('backend/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/forms/styling/uniform.min.js') }}"></script>


    <script src="{{ asset('backend/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/forms/validation/localization/messages_'.App::getLocale().'.js') }}" type="text/javascript"></script>
    <script src="{{ asset('backend/vendor/mscart/galleries/assets/js/settings.js') }}"></script>



@endsection
