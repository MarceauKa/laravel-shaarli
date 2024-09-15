<?php

namespace App\Http\Controllers\Api\Manage;

use App\Http\Controllers\Controller;
use App\Notifications\CheckEmail;
use App\Services\LinkArchive\LinkArchive;
use App\Services\LinkArchive\YoutubeDlProvider;
use App\Services\Shaark\Shaark;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FeaturesController extends Controller
{
    public function __construct()
    {
        $this->middleware('demo');
    }

    public function check(Request $request, Shaark $shaark, string $type)
    {
        if (false === in_array($type, ['pdf', 'media', 'email'])) {
            abort(404);
        }

        if ($type === 'pdf') {
            $check = $this->checkArchivePdf($shaark);
        }

        if ($type === 'media') {
            $check = $this->checkArchiveMedia($shaark);
        }

        if ($type === 'email') {
            $check = $this->checkEmail($request);
        }

        if (true !== $check) {
            return $check;
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    protected function checkArchivePdf(Shaark $shaark)
    {
        if (false === $shaark->getLinkArchivePdf()) {
            return $this->sendError(__('Archive as PDF is not enabled'));
        }

        $exec = $shaark->getNodeBin();
        exec($exec . ' --version', $result);

        if (empty($result)) {
            return $this->sendError(__('Your node path is unreachable: :path', ['path' => $exec]));
        }

        try {
            $name = LinkArchive::archive(url()->route('home'), 'pdf');
        } catch (\Exception $e) {
            return $this->sendError(__('Unable to create archive, error is: :message', ['message' => $e->getMessage()]));
        }

        Storage::disk('archives')->delete($name);

        return true;
    }

    protected function checkArchiveMedia(Shaark $shaark)
    {
        if (false === $shaark->getLinkArchiveMedia()) {
            return $this->sendError(__('Archive as Media is not enabled'));
        }

        $exec = $shaark->getYoutubeDlBin();
        exec($exec . ' --version', $result);

        if (empty($result)) {
            return $this->sendError(__('Your youtube-dl path is unreachable: :path', ['path' => $exec]));
        }

        $status = YoutubeDlProvider::test('https://www.youtube.com/watch?v=oavMtUWDBTM');

        if ($status === false) {
            return $this->sendError(__("Unknow error. Check if python is correctly installed on your system"));
        }

        return true;
    }

    protected function checkEmail(Request $request)
    {
        try {
            /** @var User $user */
            $user = $request->user();
            $user->notifyNow(new CheckEmail());
        } catch (\Exception $e) {
            return $this->sendError(__('Unable to send test email'));
        }

        return true;
    }

    protected function sendError(string $message): JsonResponse
    {
        return response()->json([
            'status' => 'fail',
            'message' => $message
        ], 422);
    }
}
