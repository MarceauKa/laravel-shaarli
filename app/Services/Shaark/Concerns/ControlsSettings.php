<?php

namespace App\Services\Shaark\Concerns;

use App\Services\Shaark\Shaark;
use Illuminate\Support\Collection;
use Spatie\Valuestore\Valuestore;

/**
 * @mixin Shaark
 * @method string getName()
 * @method string getLocale()
 * @method bool getIsPrivate()
 * @method bool getPrivateDownload()
 * @method bool getUseDefaultSearch()
 * @method string getPostsOrder()
 * @method string getAdditionalJs()
 * @method string getAdditionalCss()
 * @method bool getIsDark()
 * @method string getCustomBackground()
 * @method string getCustomIcon()
 * @method bool getLinkArchivePdf()
 * @method string getNodeBin()
 * @method string getChromiumBin()
 * @method int getArchivePdfWidth()
 * @method int getArchivePdfHeight()
 * @method bool getLinkArchiveMedia()
 * @method string getYoutubeDlBin()
 * @method string getPythonBin()
 * @method bool getSecureLogin()
 * @method bool getBackupEnabled()
 * @method bool getBackupOnlyDatabase()
 * @method bool getBackupPeriod()
 * @method bool getImagesOriginalResize()
 * @method int getImagesOriginalResizeWidth()
 * @method string getImagesThumbFormat()
 * @method bool getImagesThumbQueue()
 * @method bool getCommentsEnabled()
 * @method bool getCommentsGuestView()
 * @method bool getCommentsGuestAdd()
 * @method bool getCommentsModeration()
 * @method bool getCommentsNotification()
 * @method bool getLinkHealthChecksEnabled()
 * @method int getLinkHealthChecksAge()
 */
trait ControlsSettings
{
    /** @var Valuestore $settings */
    protected $settings;

    public function getSettingsConfig(): array
    {
        return $this->app['config']->get('shaark.settings');
    }

    public function validateDefaultSettings(): void
    {
        if ($this->settings->count() === 0) {
            foreach ($this->getSettingsConfig() as $key => $item) {
                $this->settings->put($key, $item['default']);
            }
        }
    }

    public function getSettings(): array
    {
        return array_merge(
            collect($this->getSettingsConfig())
                ->transform(function ($item, $key) {
                    return [
                        'key'     => $key,
                        'default' => $item['default'],
                    ];
                })
                ->pluck('default', 'key')
                ->toArray(),
            $this->settings->all()
        );
    }

    public function setSettings(Collection $settings): self
    {
        foreach ($this->getSettingsConfig() as $key => $item) {
            if ($key === 'custom_background') {
                $this->handleCustomBackground($settings->get('custom_background'));
                continue;
            }

            if ($key === 'custom_icon') {
                $this->handleCustomIcon($settings->get('custom_icon'));
                continue;
            }

            if (is_bool($item['default'])) {
                $this->settings->put($key, $settings->get($key, 'off') == 'on');
                continue;
            }

            $this->settings->put($key, $settings->get($key, $item['default']));
        }

        return $this;
    }

    public function cleanSettings(): self
    {
        $defaults = $this->getSettingsConfig();

        foreach ($this->settings->all() as $key => $value) {
            if (false === array_key_exists($key, $defaults)) {
                $this->settings->forget($key);
            }
        }

        return $this;
    }
}
