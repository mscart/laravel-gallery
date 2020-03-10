<?php

namespace MsCart\Galleries;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Activitylog\Traits\LogsActivity;


class Gallery extends Model
{
    use NodeTrait;
    use LogsActivity;

    protected $table = 'galleries';

    protected static $logAttributes = ['id'];



    public function gallery_name(){
        return $this->hasMany('MsCart\Galleries\GalleryDetail','gallery_id')->where('locale',\App::getLocale());
    }

    public function details()
    {
        return $this->hasMany('MsCart\Galleries\GalleryDetail','gallery_id');
    }

    public function images()
    {
        return $this->hasMany('MsCart\Galleries\GalleryImage','gallery_id');
    }

    public function getUsers()
    {
        return $this->hasMany('MsCart\Galleries\GalleryUser','gallery_id');
    }

}
