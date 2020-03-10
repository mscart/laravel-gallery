@extends('admin.app')
@section('page_title') @lang('galleries::gallery.name') @endsection
@section('pagetitle') @lang('galleries::gallery.name') @endsection
@section('breadcrumb')
    <span class="breadcrumb-item active">@lang('galleries::gallery.add_gallery')</span>
@endsection


@section('content')
    <form action="{{ route('galleries.store') }}" method="post" enctype="multipart/form-data" class="multi_validate">
        {{ csrf_field() }}
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">@lang('galleries::gallery.add_gallery')</h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            @foreach($langs as $lang=>$name)
                                <li class="nav-item"><a href="#tab_{{$lang}}" class="nav-link @if ($loop->first) active @endif" data-toggle="tab"><img src="{{asset('backend/images/lang/'.$lang.'.png')}}" class="img-flag" alt="{{$name}}"> {{$name}}</a></li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach($langs as $lang=>$name)
                                <div class="tab-pane fade show @if ($loop->first) active @endif" id="tab_{{$lang}}">
                                    <div class="form-group">
                                        <label for="">@lang('galleries::gallery.gall_name')</label>
                                        <input type="text" name="name[{{$lang}}]" id="name_{{$lang}}" class="form-control slugify" data-remote = "slug_{{$lang}}" required=""/>
                                    </div>
                                    <div class="form-group">
                                        <label for="">@lang('galleries::gallery.gall_slug')</label>
                                        <input type="text" name="slug[{{$lang}}]" id="slug_{{$lang}}" class="form-control slug_{{$lang}}" required=""/>
                                    </div>
                                    <div class="form-group">
                                        <label for="">@lang('galleries::gallery.gall_desc')</label>
                                        <textarea type="text" name="desc[{{$lang}}]" id="desc__{{$lang}}" class="form-control" required=""></textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">

                            @php($prepend = '|')
                            <label>@lang('galleries::gallery.parent')</label>
                            <select name="parent_id" id="parent_id" class=" form-control select_2 " required>
                                <option value="0">@lang('galleries::gallery.root')</option>
                                @foreach($galeries as $gal)
                                    <option value="{{$gal->id}}">{{$gal->gallery_name->first()->name}}</option>
                                    @include('galleries::partials.options', ['gal'=>$gal,"prepend"=>$prepend])
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group">

                            <label>@lang('galleries::gallery.users')</label>
                            <select name="users[]" id="users" class=" form-control multiselect" multiple required >

                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            @php($prepend = '|')
                            <label>@lang('galleries::gallery.active')</label>
                            <select name="active" id="active" class=" form-control select_2" required>
                                <option value="1">@lang('admin/general.yes')</option>
                                <option value="0">@lang('admin/general.no')</option>

                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end align-items-center">
                    <button type="reset" class="btn btn-light" id="reset">@lang('admin/general.reset') <i class="icon-reload-alt ml-2"></i></button>
                    <button type="submit" class="btn btn-primary ml-3">@lang('admin/general.submit') <i class="icon-paperplane ml-2"></i></button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom_js')
    <script src="{{ asset('backend/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script src="{{asset('backend/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{ asset('backend/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/mscart/galleries/assets/js/create.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/forms/validation/localization/messages_'.App::getLocale().'.js') }}" type="text/javascript"></script>

@endsection
