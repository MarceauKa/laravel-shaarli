<?php

return [
    // Hashids configuration
    'hashids' => [
        'salt' => env('HASHIDS_SALT', 'default-salt'),
        'min' => env('HASHIDS_MIN_LENGTH', 10),
    ],
    // Activate demo mode
    'demo' => env('APP_DEMO', false),
    // Default settings
    'settings' => [
        'name' => [
            'default' => env('APP_NAME'),
            'rules' => ['required', 'min:2', 'max:100']
        ],
        'locale' => [
            'default' => env('APP_LANG'),
            'rules' => ['required', 'in:fr,en,de,ja,nl']
        ],
        'is_private' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'private_download' => [
            'default' => true,
            'rules' => ['nullable', 'in:on,off']
        ],
        'use_default_search' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'posts_order' => [
            'default' => 'created',
            'rules' => ['required', 'in:created,updated'],
        ],
        'additional_js' => [
            'default' => null,
            'rules' => ['nullable'],
        ],
        'additional_css' => [
            'default' => null,
            'rules' => ['nullable'],
        ],
        'is_dark' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'custom_background' => [
            'default' => null,
            'rules' => ['nullable'],
        ],
        'custom_icon' => [
            'default' => '/images/logo-shaark.png',
            'rules' => ['nullable', 'image', 'mimes:png', 'dimensions:width=512,height=512'],
        ],
        'secure_login' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'secure_code_expires' => [
            'default' => 30,
            'rules' => ['required', 'numeric', 'min:5', 'max:300']
        ],
        'secure_code_length' => [
            'default' => 8,
            'rules' => ['required', 'numeric', 'min:4', 'max:12']
        ],
        'link_archive_pdf' => [
            'default' => true,
            'rules' => ['nullable', 'in:on,off']
        ],
        'link_archive_media' => [
            'default' => true,
            'rules' => ['nullable', 'in:on,off']
        ],
        'node_bin' => [
            'default' => '/usr/bin/node',
            'rules' => ['required']
        ],
        'archive_pdf_width' => [
            'default' => '1440',
            'rules' => ['required', 'numeric', 'min:100', 'max:3840']
        ],
        'archive_pdf_height' => [
            'default' => '960',
            'rules' => ['required', 'numeric', 'min:100', 'max:2160']
        ],
        'youtube_dl_bin' => [
            'default' => '/usr/bin/yt-dlp',
            'rules' => ['required']
        ],
        'python_bin' => [
            'default' => '/usr/bin/python',
            'rules' => ['required']
        ],
        "chromium_bin" => [
          "default" => '/usr/bin/chromium-browser',
          "rules" => ['required']
        ],
        'backup_enabled' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'backup_only_database' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'backup_period' => [
            'default' => 'daily',
            'rules' => ['required', 'in:daily,weekly']
        ],
        'images_original_resize' => [
            'default' => true,
            'rules' => ['nullable', 'in:on,off'],
        ],
        'images_original_resize_width' => [
            'default' => 2000,
            'rules' => ['required', 'numeric', 'min:500', 'max:5000'],
        ],
        'images_thumb_format' => [
            'default' => 'square',
            'rules' => ['required', 'in:square,original']
        ],
        'images_thumb_queue' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'comments_enabled' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'comments_guest_view' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'comments_guest_add' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'comments_moderation' => [
            'default' => 'whitelist',
            'rules' => ['nullable', 'in:disabled,whitelist,all']
        ],
        'comments_notification' => [
            'default' => 'whitelist',
            'rules' => ['nullable', 'in:disabled,whitelist,all']
        ],
        'link_health_checks_enabled' => [
            'default' => false,
            'rules' => ['nullable', 'in:on,off']
        ],
        'link_health_checks_age' => [
            'default' => '7',
            'rules' => ['required', 'numeric', 'min:7', 'max:365']
        ],
    ],
];
