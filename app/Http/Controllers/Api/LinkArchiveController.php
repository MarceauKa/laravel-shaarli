<?php

namespace App\Http\Controllers\Api;

use App\Events\LinkArchiveRequested;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Services\LinkArchive\LinkArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LinkArchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'demo']);
    }

    public function get(Request $request, int $id)
    {
        /** @var Link $link */
        $link = Link::findOrFail($id);

        return response()->json([
            'download' => $link->hasArchive() ? route('link.archive.download', $link->id) : null,
            'providers' => LinkArchive::availableFor($link->url)
        ]);
    }

    public function store(Request $request, int $id)
    {
        /** @var Link $link */
        $link = Link::findOrFail($id);
        event(new LinkArchiveRequested($link, $request->get('type')));

        return response()->json([
            'status' => 'created',
            'message' => __('Link is being archived')
        ]);
    }

    public function delete(Request $request, int $id)
    {
        /** @var Link $link */
        $link = Link::findOrFail($id);

        $file = $link->archive;

        if ($file && Storage::disk('archives')->exists($file)) {
            Storage::disk('archives')->delete($file);
            $link->archive = null;
            $link->save();

            return response()->json([
                'status' => 'deleted',
                'message' => __('Deleted')
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('Archive does not exist')
        ]);
    }
}
