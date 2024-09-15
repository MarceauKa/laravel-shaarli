<?php

namespace App\Services\LinkArchive;

class LinkArchive
{
    /** @var array $providers */
    public static $providers = [
        'media' => YoutubeDlProvider::class,
        'pdf' => BrowsershotProvider::class,
    ];

    public static function availableFor(string $url): array
    {
        $providers = [];

        foreach (self::$providers as $type => $provider) {
            /** @var BaseProvider $provider */
            $provider = new $provider($url);

            if ($provider->isEnabled() && $provider->canArchive()) {
                $providers[] = $type;
            }
        }

        return $providers;
    }

    public static function archive(string $url, string $provider): ?string
    {
        if (false === array_key_exists($provider, self::$providers)) {
            throw new \RuntimeException("Unrecognized provider: $provider");
        }

        $class = self::$providers[$provider];
        /** @var BaseProvider $provider */
        $provider = new $class($url);

        return $provider->makeArchive();
    }
}
