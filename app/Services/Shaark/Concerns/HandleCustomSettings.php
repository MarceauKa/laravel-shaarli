<?php

namespace App\Services\Shaark\Concerns;

use App\Services\Shaark\Shaark;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Shaark
 */
trait HandleCustomSettings
{
    public function handleCustomIcon(?UploadedFile $value): void
    {
        if (empty($value)) {
            return;
        }

        $name = sprintf('logo-%s.png', uniqid());
        $value->storeAs('/', $name, ['disk' => 'public']);
        $this->settings->put('custom_icon', $name);

        return;
    }

    public function getCustomIconUrl(): string
    {
        $icon = $this->getCustomIcon();

        if ($icon === $this->getSettingsConfig()['custom_icon']['default']) {
            return url($icon);
        }

        return Storage::disk('public')->url($icon);
    }

    public function handleCustomBackground($value): void
    {
        if( $value === null)
            return;
        $data = (array)json_decode($value);
        if ($data['type'] === 'gradient') {
            $this->settings->put('custom_background', json_encode($data));
            return;
        } else if ($data['type'] === 'image' && ! empty($data['base64'])) {
            if (false !== preg_match('/data:image\/([a-z]+);.*/i', $data['base64'], $ext)) {
                $ext = $ext[1];
                $base64 = str_replace('data:image/'.$ext.';base64,', '', $data['base64']);
                $file = base64_decode($base64);
                $name = 'custom-background.'.$ext;

                Storage::disk('public')->put($name, $file);
                $url = Storage::disk('public')->url($name);

                $this->settings->put('custom_background', json_encode([
                    'type' => 'image',
                    'file' => $url,
                ]));
            }

            return;
        } else if ($data['type'] === 'none') {
            $this->settings->put('custom_background', $value);
            return;
        }

        return;
    }

    public function getCustomBackgroundCss(): string
    {
        $background = (array)json_decode($this->settings->get('custom_background'));

        if (! array_key_exists('type', $background)) {
            return '';
        }

        if ($background['type'] === 'gradient') {
            return vsprintf("linear-gradient(%sdeg, %s 0%%, %s 100%%)", [
                $background['orientation'],
                $background['start'],
                $background['end'],
            ]);
        }

        if ($background['type'] === 'image') {
            return vsprintf("url(%s)", [
                $background['file'],
            ]);
        }

        return '';
    }
}
