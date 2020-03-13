@extends('admin.app')
@section('page_title') @lang('galleries::gallery.list') @endsection
@section('pagetitle') @lang('galleries::gallery.list') @endsection
@section('breadcrumb')
    <span class="breadcrumb-item active">@lang('galleries::gallery.list')</span>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">

                    <span class="text-uppercase font-size-sm font-weight-semibold">{{$gData->gallery_name->first()->name}}</span>
                    <div class="header-elements">
                        <div class="list-icons">
                            <button class="btn bg-danger " style="display:none;"  id="delete_selected"><span><i class="icon-trash"></i> @lang('admin/general.actions.delete_selected')</span></button>
                            <button class="btn bg-primary " style="display:none;"  id="move_selected"><span><i class="icon-move"></i> @lang('admin/general.actions.move_selected')</span></button>
                            <input type="checkbox" value="0" class="form-check-input-styled " id="check_all" autocomplete="off" />


                        </div>
                    </div>
                </div>
                <form action="{{route("galleries.deleteImage",'0')}}" method="post" enctype="multipart/form-data">
                    <div class="card-body" id="browse_images">
                    <div id="log"></div>
                        <div class="row">

                                @foreach($gallery_images as $image)
                                    <div class="col-sm-6 col-lg-3 col-md-3">
                                        <div class="card">
                                            <div class="card-img-actions  bg-transparent m-1">
                                                @if($image->type=='video')
                                                    <video class="card-img embed-responsive embed-responsive-16by9 video" style="cursor: pointer" src="{{asset( '/storage/galeries/'.$gData->id.'/'.$image->image)}}"></video>

                                                @else
                                                    <img class="card-img img-fluid " src="{{asset( '/storage/galeries/'.$gData->id.'/thumbs/'.$image->image)}}" alt="">
                                                    <div class="card-img-actions-overlay card-img">
                                                        <a href="{{asset( '/storage/galeries/'.$gData->id.'/'.$image->image)}}" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
                                                            <i class="icon-eye"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex align-items-start flex-nowrap">
                                                    <div>
                                                        <div class="font-weight-semibold mr-2">{{ substr(str_replace($gData->id.'_','',$image->image),0,20)}}</div>
                                                        <span class="font-size-sm text-muted">@lang('admin/general.size') {{ formatBytes($image->size) }}</span>
                                                    </div>
                                                    <div class="list-icons list-icons-extended ml-auto">
                                                        <input type="checkbox" value="{{$image->id}}" class="form-check-input-styled checkable-row" autocomplete="off" />
                                                        <a href="{{route('galleries.downloadImage',$image->id)}}" class="list-icons-item "><i class="icon-download top-0"></i></a>
                                                        <a href="#" class="list-icons-item text-danger delete"><i class="icon-bin top-0"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                        </div>
                    </div>
                </form>
                <div class="card-footer bg-transparent d-flex justify-content-between border-top-0 pt-0">
                    {{ $gallery_images->appends($_GET)->links() }}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <form action="{{route('galleries.manage',$gData->id)}}" method="get" >

                <div class="card ">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="text-uppercase font-size-sm font-weight-semibold">@lang('galleries::gallery.filter_type')</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label>Item type</label>
                            <select name="itemType" class="form-control select_2">
                                <option  value="">@lang('galleries::gallery.all')</option>
                                <option @if(request()->get('itemType') == 'video' ) selected @endif  value="video">@lang('galleries::gallery.video')</option>
                                <option @if(request()->get('itemType') == 'image' ) selected @endif value="image">@lang('galleries::gallery.image')</option>
                                <option @if(request()->get('itemType') == 'audio' ) selected @endif value="image">@lang('galleries::gallery.audio')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Order</label>
                            <select name="orderBy" class="form-control select_2">
                                <option value="">@lang('galleries::gallery.select')</option>
                                <option @if(request()->get('orderBy') == 'id' ) selected @endif value="id">@lang('galleries::gallery.id')</option>
                                <option @if(request()->get('orderBy') == 'image' ) selected @endif  value="image">@lang('galleries::gallery.name')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Order</label>
                            <select name="direction" class="form-control select_2">
                                <option value="">@lang('galleries::gallery.select')</option>
                                <option @if(request()->get('direction') == 'desc' ) selected @endif value="desc">@lang('galleries::gallery.desc')</option>
                                <option @if(request()->get('direction') == 'asc' ) selected @endif  value="asc">@lang('galleries::gallery.asc')</option>
                            </select>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="icon-filter3"></i>@lang('galleries::gallery.filter')  </button>
                    </div>
                </div>
            </form>
            <div class="card ">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="text-uppercase font-size-sm font-weight-semibold">@lang('galleries::gallery.add_images')</span>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="file-uploader"></div>
                </div>
            </div>
        </div>
    </div>
@include('admin.partials.modal_delete')
    @include('galleries::partials.move_images_modal')
@endsection
@section('custom_js')
    <script src="{{ asset('backend/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/ui/sticky.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/uploaders/plupload/plupload.full.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/uploaders/plupload/plupload.queue.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/media/fancybox.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/uploaders/plupload/i18n/'.App::getLocale().'.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/forms/validation/localization/messages_'.App::getLocale().'.js') }}" type="text/javascript"></script>
    <script src="{{ asset('backend/vendor/mscart/galleries/assets/js/manager.js') }}"></script>
    <script>
        var upload_url ="{{route('galleries.uploadFile',$gData->id)}}"
        var ajax_url = "{{route('galleries.deleteImage','test')}}";
        var settings_chuck_size ="{{config('settings.settings_chuck_size')}}";
        var settings_max_file_size ="{{config('settings.settings_max_file_size')}}";
        var settings_allowed_extensions ="{{config('settings.settings_allowed_extensions')}}";
    </script>


@endsection
