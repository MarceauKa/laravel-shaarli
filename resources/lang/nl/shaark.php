<?php

return [
    // Settings
    'settings' => [
        'general' => [
            'title' => 'Algemeen',
            'install_button' => 'Installeren',
            'site_name' => 'Site naam',
            'lang' => 'Taal',
            'private_help' => 'Priv&eacute; inhoud (alle inhoud is priv&eacute; en inloggen is verplicht)',
            'private_download' => 'Downloads priv&eacute; houden (links en albums)',
            'use_default_search' => 'Gebruik klassieke SQL-zoeken in plaats van full-text zoeken',
            'posts_order' => 'Voorkeur voor berichten volgorde',
            'created' => 'Aanmaakdatum',
            'updated' => 'Laatst bijgewerkt',
            'additional_js' => 'Extra JS',
            'additional_css' => 'Extra CSS',
        ],

        'appearance' => [
            'title' => 'Uiterlijk',
            'is_dark' => 'Donkere modus',
            'custom_background' => 'Eigen achtergrond',
            'custom_icon' => 'Eigen pictogram (512x512, .png)',
        ],

        '2fa' => [
            'title' => 'Beveiligde login',
            'check_email' => 'Test e-mail',
            'secure_login' => '2-FA login (gebruik een code die per e-mail wordt verzonden)',
            'secure_code_expires' => 'Verlopen van veiligheidscode (in minuten)',
            'secure_code_length' => 'Lengte veiligheidscode',
        ],

        'archiving' => [
            'title' => 'Archiveren',
            'link_archive_pdf' => 'PDF archiveren (Web pagina\'s naar PDF)',
            'node_bin' => 'Node.js binary',
            'chromium_bin' => "Chromium binary",
            'archive_pdf_width' => 'Paginabreedte',
            'archive_pdf_height' => 'Paginahoogte',
            'link_archive_media' => 'Media archiveren (Youtube, Soundcloud, ...)',
            'youtube_dl_bin' => 'Youtube-dl binary',
            'python_bin' => 'Python binary',
            'check_pdf_archiving' => 'PDF archiveren testen',
            'check_media_archiving' => 'Media archiveren testen',
        ],

        'backup' => [
            'title' => 'Backup',
            'enabled' => 'Backup gebruiken?',
            'enabled_help' => 'Controleer of de backup correct is geconfigureerd.',
            'only_database' => 'Alleen database opslaan?',
            'period' => 'Backup periode',
            'period_daily' => 'Dagelijks',
            'period_weekly' => 'Wekelijks',
        ],

        'images' => [
            'title' => 'Afbeeldingen',
            'images_original_resize' => 'Originele afbeelding verkleinen?',
            'images_original_resize_width' => 'Originele maximale breedte in pixels',
            'images_thumb_format' => 'Thumbnail formaat',
            'format_square' => 'Vierkant',
            'format_original' => 'Origineel',
            'images_thumb_queue' => 'Thumbnail genereren op de achtergrond?',
        ],

        'comments' => [
            'title' => 'Reacties',
            'comments_enabled' => 'Reacties gebruiken',
            'comments_guest_view' => 'Gasten kunnen reacties bekijken',
            'comments_guest_add' => 'Gasten kunnen reacties toevoegen',
            'comments_moderation' => 'Nieuwe reactie controle',
            'comments_notification' => 'Nieuwe reactie notificatie',
            'disabled' => 'Uitgeschakeld',
            'whitelist' => 'White-listing',
            'all' => 'Allemaal',
        ],

        'links' => [
            'title' => 'Link Health Checks',
            'health_checks_enabled' => 'Schakel gezondheidscontroles in',
            'health_checks_age' => 'Aantal dagen tussen controles voor elke link',
        ],
    ],

    // Mails
    'mails' => [
        '2fa' => [
            'title' => 'Beveilig je login',
            'message' => 'Gebruik de volgende veiligheidscode :code om je login te bevestigen.',
            'button' => 'Login bevestigen',
        ],

        'check' => [
            'title' => 'Het werkt!',
            'message' => 'Deze mail is gestuurd door :name om de e-mailconfiguratie te testen.',
        ],

        'comment' => [
            'title' => 'Nieuwe reactie',
            'message' => 'Er is een nieuwe reactie van ":name" (:email) op bericht ":post".',
            'action' => 'Bekijk',
        ],

        'unmoderated' => [
            'title' => 'Nieuwe reactie om te controleren',
            'message' => 'Er is een nieuwe reactie van ":name" (:email) op bericht ":post" die gecontroleerd moet worden.',
            'action' => 'Bekijk and controleer',
        ]
    ],
];
