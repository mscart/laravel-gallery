<form action="{{route('galleries.moveImagesToCategs')}}" class="multi_validate" id="moveImages" method="post">
<div class="modal fade" id="move_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmation">
                    @lang('admin/general.move_confirmation_title')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">
                    &times;
                </span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    @php($prepend = '|')
                    <label>@lang('galleries::gallery.to_move')</label>
                    <select name="gallery_id" id="gallery_id" class="select_2 form-control" autocomplete="off" required>
                        <option selected value="">@lang('galleries::gallery.choose')</option>
                        @php($galeries = \MsCart\Galleries\Gallery::get()->toTree())
                        @foreach($galeries as $gal)
                            <option @if($gal->id == $gData->id) disabled @endif value="{{$gal->id}}">{{$gal->gallery_name->first()->name}}</option>
                            @include('galleries::partials.options', ['gal'=>$gal,"prepend"=>$prepend])
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel_delete" data-dismiss="modal">
                    @lang('admin/general.actions.cancel')
                </button>
                <input type="hidden" name="images_id" id="images_id" />
                {{ csrf_field() }}


                    <button type="submit" class="btn btn-primary modalMoveButton">
                        @lang('admin/general.actions.move')
                    </button>

            </div>
        </div>
    </div>
</div>
</form>
