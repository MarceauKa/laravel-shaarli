<?php

namespace App\Services\LinkArchive;

use YoutubeDl\Entity\Video;
use YoutubeDl\Entity\VideoCollection;
use YoutubeDl\Exception\ExecutableNotFoundException;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class YoutubeDlProvider extends BaseProvider
{
    public function makeArchive(): ?string
    {
        $path = storage_path('app/archives/');

        if (is_dir($path) === false) {
            mkdir($path);
        }

        try {
            $dl = new YoutubeDl();

            $dl->setPythonPath(app('shaark')->getPythonBin());
            $dl->setBinPath(app('shaark')->getYoutubeDlBin());

            /*$dl->onProgress(function ($progress) {
                $percentage = $progress['percentage'];
                $size = $progress['size'];
                logger()->debug("Percentage: $percentage; Size: $size");
            });*/

            $result = $dl->download(Options::create()
                ->format('best')
                ->restrictFileNames(true)
                ->maxDownloads(1)
                ->noCheckCertificate(true)
                ->output(md5($this->url) . '.%(ext)s')
                ->downloadPath($path)
                ->url($this->url));

            if ($result->count() < 1) {
                return null;
            }

            logger()->debug('Link media archive', $result->toArray());
            return str_replace($path,"",$result->getVideos()[0]->getFilename());
        } catch (ExecutableNotFoundException $e) {
            throw new \RuntimeException("Unable to create link media archive", 0, $e);
        }
    }

    public function isEnabled(): bool
    {
        return app('shaark')->getLinkArchiveMedia() === true;
    }

    public function canArchive(): bool
    {
        return true;
    }

    public static function test(string $url): bool
    {
        try {
            $dl = new YoutubeDl();

            $dl->setPythonPath(app('shaark')->getPythonBin());
            $dl->setBinPath(app('shaark')->getYoutubeDlBin());

            $result = $dl->download(Options::create()
                ->skipDownload(true)
                ->restrictFileNames(true)
                ->maxDownloads(1)
                ->noCheckCertificate(true)
                ->downloadPath(storage_path('app/archives'))
                ->url($url));

            if (false === $result instanceof VideoCollection && $result->count() >= 1) {
                return false;
            }
        } catch (ExecutableNotFoundException $e) {
            unset($e);
            return false;
        }

        return true;
    }
}
