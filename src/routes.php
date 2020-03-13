<?php


Route::group(['prefix'  =>  config('app.admin_prefix')], function () {

    Route::group(['middleware' => ['web','auth:admin']], function () {

        Route::get('/galleries', '\MsCart\Galleries\GalleriesController@index')->name('categories.index');
        Route::get('/galleries/settings', '\MsCart\Galleries\GalleriesController@showSettings')->name('galleries.showSettings');
        Route::post('/galleries/settings', '\MsCart\Galleries\GalleriesController@saveSettings')->name('galleries.saveSettings');
        Route::get('/galleries/{gallery_id}/manage', '\MsCart\Galleries\GalleriesController@manage')->name('galleries.manage');
        Route::post('/galleries/getGalleries', '\MsCart\Galleries\GalleriesController@getGalleries')->name('galleries.getGalleries');
        Route::resource('/galleries', '\MsCart\Galleries\GalleriesController');

        Route::post('/galleries/{gallery_id}/upload','\MsCart\Galleries\GalleriesController@uploadFile')->name('galleries.uploadFile');
        Route::delete('/galleries/image/{image_id}/delete','\MsCart\Galleries\GalleriesController@deleteImage')->name('galleries.deleteImage');
        Route::get('/galleries/image/{image_id}/download','\MsCart\Galleries\GalleriesController@downloadImage')->name('galleries.downloadImage');
        Route::post('/galleries/move-images-to-categs','\MsCart\Galleries\GalleriesController@moveImagesToCategs')->name('galleries.moveImagesToCategs');

    });

});
