
@foreach($gal->children as $gal)
    @php($prepend .= '_');
        <option @if(isset($gData) && $gal->id == $gData->id ) disabled @endif @if( Route::currentRouteName()=='galleries.edit' && $gData->parent &&  $gal->id == $gData->parent->id) selected @endif value="{{$gal->id}}">{{$prepend}} {{$gal->gallery_name->first()->name}}</option>
    @include('galleries::partials.options', ['gal'=>$gal,"prepend"=>$prepend])
@endforeach
