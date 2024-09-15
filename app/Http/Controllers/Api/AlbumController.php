<?php

namespace App\Http\Controllers\Api;

use App\Models\Album;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\StoreAlbumUploadRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\Shaark\Shaark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'demo']);
    }

    public function store(StoreAlbumRequest $request)
    {
        $data = collect($request->validated());

        /** @var Album $album */
        $album = Album::create($data->only([
            'title',
            'content',
        ])->toArray());

        $post = new Post();
        $post->is_pinned = $data;
        $post->is_private = $data;
        $post->user_id = $request->user()->id;
        $post->postable()->associate($album)->save();

        if ($data['tags']) {
            $post->syncTags($data['tags']);
        }

        $post->save();

        $this->processUploadedImages($data->get('uploaded'), $album);

        return response()->json([
            'post' => new PostResource($post),
            'status' => 'created',
        ]);
    }

    public function update(StoreAlbumRequest $request, int $id)
    {
        /** @var Album $album */
        $album = Album::findOrFail($id);
        $data = collect($request->validated());

        $album->fill($data->only('title', 'content')->toArray());

        $album->post->is_pinned = $data->get('is_pinned', $album->post->is_pinned);
        $album->post->is_private = $data->get('is_private', $album->post->is_private);
        $album->post->save();

        if ($data['tags']) {
            $album->post->syncTags($data['tags']);
        }

        $album->save();
        $album->post->save();

        $this->processExistingImages($data->get('images'), $album);
        $this->processUploadedImages($data->get('uploaded'), $album);

        return response()->json([
            'post' => new PostResource($album->post),
            'status' => 'updated',
        ]);
    }

    public function delete(Request $request, int $id)
    {
        /** @var Album $album */
        $album = Album::with('post')->findOrFail($id);

        $album->delete();
        $album->post->delete();

        return response()->json([
            'id' => $album->id,
            'status' => 'deleted',
        ]);
    }

    public function upload(StoreAlbumUploadRequest $request, Shaark $shaark)
    {
        $file = $request->file('filepond');

        if ($shaark->getImagesOriginalResize() === true) {
            $size = $shaark->getImagesOriginalResizeWidth();

            Image::load($file)
                ->fit(Manipulations::FIT_MAX, $size, $size)
                ->save();
        }

        return Storage::put('tmp', $file);
    }

    public function processUploadedImages(array $images, Album $model): void
    {
        $images = collect($images)
            ->reject(function ($item) {
                return substr($item, 0, 4) !== 'tmp/';
            });

        $images->each(function ($item) use ($model) {
            $model->addMedia(Storage::path($item))
                  ->toMediaCollection('images');

            Storage::delete($item);
        });
    }

    public function processExistingImages(array $images, Album $model): void
    {
        $images = collect($images);
        $medias = $model->getMedia('images');

        foreach ($medias as $media) {
            $image = $images->firstWhere('name', $media->name);

            if (! $image) {
                $media->delete();
            }
        }

        foreach ($images as $image) {
            $media = $medias->firstWhere('name', $image['name']);

            if ($media) {
                $media->order_column = $image['order'];
                $media->save();
            }
        }
    }
}
