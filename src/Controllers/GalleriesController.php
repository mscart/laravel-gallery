<?php

namespace MsCart\Galleries;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Datatables;

class GalleriesController extends Controller
{

    /**
     * CategoriesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    /**
     * Get galleries from database
     *
     */
    public function getGalleries()
    {
        $galeries = Gallery::orderBy('sort_order', "desc")->orderBy('id', "desc")->get();
        return Datatables::of($galeries)
            ->rawColumns(['active','action', 'id'])
            ->addColumn('id', 'galleries::partials.check_boxes')
            ->addColumn('action', 'galleries::partials.table_actions')
            ->addColumn('active', 'galleries::partials.badge')
            ->addColumn('name',function($data){
                return $data->gallery_name->first()->name;
            })
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('galleries::role.read');
        return view('galleries::index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        //
        $this->authorize('galleries::role.create');
        $langs = config('app.locales');

        $galeries = Gallery::get()->toTree();
        $users = User::all();

        return view('galleries::create', compact(['langs','galeries','users']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {

        $this->authorize('galleries::role.create');

        $parent = Gallery::find($request->parent_id);
        $g = new Gallery();
        $g->active = $request->active;
        $g->sort_order = 0;
        if ($parent)
             $g->appendToNode($parent)->save();
        else
            $g->saveAsRoot();
        //save the details
        foreach ($request->name as $lang=>$val){
            $d = new GalleryDetail();
            $d->gallery_id = $g->id;
            $d->name = $val;
            $d->slug = $request->slug[$lang];
            $d->locale= $lang;
            $d->description = $request->desc[$lang];
            $d->saveOrFail();
        }

        //attach gallery to users

        if (count($request->users)>0)
        {
            foreach ($request->users as $user_id)
            {
                $u = new GalleryUser();
                $u->gallery_id=$g->id;
                $u->user_id = $user_id;
                $u->saveOrFail();
            }
        }
        $viewData = ['messages' => [['message' => trans('galleries::gallery.messages.saved', ['name' => '']) , 'type' => 'success'], ], ];
        return redirect()
            ->route('galleries.manage',$g->id)
            ->with($viewData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $this->authorize('galleries::role.edit');
        $langs = config('app.locales');

        $galeries = Gallery::get()->toTree();
        $gData = Gallery::find($id);
        $gUsers = $gData->getUsers->pluck('user_id')->toArray();

        $users = User::all();
        foreach ($langs as $lang=>$val)
        {
            $gd = GalleryDetail::where('locale',$lang)->where('gallery_id',$id)->first();

            $gDetails[$lang]['name'] = $gd->name;
            $gDetails[$lang]['slug'] = $gd->slug;
            $gDetails[$lang]['desc'] = $gd->description;
        }
//        $result = $gData->parent;
//        dd($result);
        return view('galleries::edit', compact(['langs','galeries','gData','gDetails','gUsers','users']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {
        $this->authorize('galleries::role.edit');

        $parent = Gallery::find($request->parent_id);
        $g = Gallery::find($id);
        $g->active = $request->active;
        $g->sort_order = 0;
        if ($parent)
            $g->parent()->associate($parent)->save();
            //$g->appendToNode($parent)->save();
        else
            $g->saveAsRoot();

        //delete old details of current gallery
        GalleryDetail::where('gallery_id',$g->id)->delete();
        //save the details
        foreach ($request->name as $lang=>$val){
            $d = new GalleryDetail();
            $d->gallery_id = $g->id;
            $d->name = $val;
            $d->slug = $request->slug[$lang];
            $d->locale= $lang;
            $d->description = $request->desc[$lang];
            $d->saveOrFail();
        }

        GalleryUser::where('gallery_id',$g->id)->delete();
        if (count($request->users)>0)
        {
            foreach ($request->users as $user_id)
            {
                $u = new GalleryUser();
                $u->gallery_id=$g->id;
                $u->user_id = $user_id;
                $u->saveOrFail();
            }
        }


        $viewData = ['messages' => [['message' => trans('galleries::gallery.messages.updated', ['name' => '']) , 'type' => 'success'], ], ];
        return redirect()
            ->route('galleries.index')
            ->with($viewData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request,$id)
    {
        $this->authorize('galleries::gallery.role.delete');

        $ids = explode(',', $request->get('ids'));
        $messages = [];
        $ids_error = [];
        $deleted = false;
        foreach ($ids as $id)
        {
            if (empty($gallery = Gallery::find($id)) || count($gallery->children) > 0 )
            {
                $ids_error[] = $id;
                $messages[] = trans('galleries::gallery.messages.not_deleted', ["name" => $gallery->name]);
                continue;
            }

            $deleted = $gallery->delete();

            if (!$deleted)
            {
                $messages[] = trans('galleries::gallery.messages.not_deleted', ["name" => $gallery->name]);
                $ids_error[] = $id;
            }

            if ($deleted)
            {
                Storage::deleteDirectory('public/galeries/'.$id);
            }
        }

        if ($deleted)
        {
            $messages[] = trans('galleries::gallery.messages.multiple_deleted');
        }

        if ($request->ajax())
        {
            return response()
                ->json(['success' => true, 'deleted' => $deleted, 'messages' => $messages, 'ids_error' => (count($ids_error) > 0) ? $ids_error : false,

                ], 200);
        }
    }

    public function manage($id)
    {
        $gallery_images = GalleryImage::where('gallery_id',$id)->paginate(20);
        $gData = Gallery::find($id);

        return view('galleries::manager', compact(['gallery_images','gData']));
    }

    public function uploadFile(Request $request,$id)
    {

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        @set_time_limit(5 * 60);


        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }


        // Settings
        $targetDir = Storage::path('public/galeries/'.$id);
        $thumbsDir = $targetDir.'/thumbs';
//$targetDir = 'uploads';
        $cleanupTargetDir = false; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds


// Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }
        if (!file_exists($thumbsDir)) {
            @mkdir($thumbsDir);
        }

// Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }

        $filePath = $targetDir . '/' . $fileName;

// Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
// Open temp file
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);

// Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);
            $mime = mime_content_type($filePath);
            if(strstr($mime, "video/")){
                $filetype = "video";
            }else if(strstr($mime, "image/")){
                $filetype = "image";
            }else if(strstr($mime, "audio/")){
                $filetype = "audio";
            }

            if ($filetype == 'image') {
                //making thumbs test
                $img = Image::make($filePath);
                $img->resize(320, 240);
                $img->save($thumbsDir . '/' . $fileName);
            }

            $size = realFileSize($filePath);
            $gi = new GalleryImage();
            $gi->gallery_id = $id;
            $gi->image = $fileName;
            $gi->type = $filetype;
            $gi->size = $size;
            $gi->saveOrFail();


        }

    }

    public function downloadImage($id)
    {
        $file = GalleryImage::find($id);
        $filePath = Storage::path('public/galeries/'.$file->gallery_id.'/'.$file->image);
        $headers = ['Content-Type: '.mime_content_type($filePath)];
        return response()->download($filePath, $file->image, $headers);

    }

    public function deleteImage(Request $request,$id)
    {
        $this->authorize('galleries::gallery.role.delete');

        $ids = explode(',', $request->get('ids'));
        $messages = [];
        $ids_error = [];
        $deleted = false;
        foreach ($ids as $id)
        {
            if (empty($image = GalleryImage::find($id)) )
            {
                $ids_error[] = $id;
                $messages[] = trans('galleries::gallery.messages.not_deleted');
                continue;
            }

            $deleted = $image->delete();

            if (!$deleted)
            {
                $messages[] = trans('galleries::gallery.messages.not_deleted');
                $ids_error[] = $id;
            }
            if ($deleted) {
                Storage::delete('public/galeries/' . $image->gallery_id . '/' . $image->image);
                Storage::delete('public/galeries/' . $image->gallery_id . '/thumbs/' . $image->image);
            }
        }

        if ($deleted)
        {

            $messages[] = trans('galleries::gallery.messages.multiple_deleted');
        }

        if ($request->ajax())
        {
            return response()
                ->json(['success' => true, 'deleted' => $deleted, 'messages' => $messages, 'ids_error' => (count($ids_error) > 0) ? $ids_error : false,

                ], 200);
        }
    }

    public function moveImagesToCategs(Request $request)
    {
        $ids = explode(',', $request->images_id);
        $gallery_id = $request->gallery_id;
        if(count($ids) > 0)
        {
            foreach ($ids as $id)
            {
                $image = GalleryImage::find($id);
                $old_gallery_id = $image->gallery_id;
                $image->gallery_id = $gallery_id;
                $image->saveOrFail();
                Storage::move('public/galeries/'.$old_gallery_id.'/'.$image->image, 'public/galeries/'.$gallery_id.'/'.$image->image);
            }
        }
        $viewData = ['messages' => [['message' => trans('galleries::gallery.messages.moved') , 'type' => 'success'], ], ];
        return redirect()
            ->route('galleries.manage', $old_gallery_id)
            ->with($viewData);
    }
}
