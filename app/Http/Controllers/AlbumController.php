<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        return view('form-album')->with([
            'page_title' => __('Add album'),
            'album' => null,
        ]);
    }

    public function edit(Request $request, int $id)
    {
        $album = Album::with('post.tags')->findOrFail($id);

        return view('form-album')->with([
            'page_title' => __('Update album'),
            'album' => $album,
        ]);
    }

    public function download(Request $request, int $id)
    {
        /** @var Album $album */
        $album = Album::findOrFail($id);

        if (false === $album->canDownloadArchive()) {
            abort(404);
        }

        $name = sprintf('album-%s.zip', Str::slug($album->title));

        if (Storage::exists('tmp/' . $name)) {
            return Storage::download('tmp/' . $name, $name);
        }

        $zip = new \ZipArchive();

        if ($zip->open(storage_path('app/tmp/' . $name), \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            $medias = $album->getMedia('images');

            foreach ($medias as $item) {
                /** @var Media $item */
                $zip->addFile($item->getPath(), $item->file_name);
                $zip->setCompressionName($item->file_name, \ZipArchive::CM_STORE);
            }

            $zip->close();
        }

        return $this->download($request, $id);
    }
}
