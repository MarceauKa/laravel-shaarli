<?php

return [
    // Settings
    'settings' => [
        'general' => [
            'title' => 'Allgemeines',
            'install_button' => 'Installieren',
            'site_name' => 'Name der Website',
            'lang' => 'Sprache',
            'private_help' => 'Privater Inhalt (alle Inhalte sind privat und eine Anmeldung ist erforderlich)',
            'private_download' => 'Downloads privat halten (Links und Alben)',
            'use_default_search' => 'Verwenden Sie die klassische SQL-Suche anstelle der Volltextsuche',
            'posts_order' => 'Bevorzugte Reihenfolge der Beiträge',
            'created' => 'Erstellungsdatum',
            'updated' => 'Datum des letzten Updates',
            'additional_js' => 'Zusätzliche JS',
            'additional_css' => 'Zusätzliche CSS',
        ],

        'appearance' => [
            'title' => "Aussehen",
            'is_dark' => "Dark Mode",
            'custom_background' => "Benutzerdefiniertes Hintergrundbild",
            'custom_icon' => "Benutzerdefiniertes Symbol (PNG, 512px)",
        ],

        '2fa' => [
            'title' => "sichere Anmeldung",
            'secure_login' => "Zwei-Faktor-Authentifizierung (erfordert einen per E-Mail gesendeten Code)",
            'secure_code_expires' => "Ablauf des Sicherheitscodes (in Minuten)",
            'secure_code_length' => "Länge des Sicherheitscodes",
        ],

        'archiving' => [
            'title' => "Archivierung",
            'link_archive_pdf' => "PDF-Archivierung (Webseiten zu PDF)",
            'node_bin' => "ausführbare Node.js-Datei",
            'chromium_bin' => "ausführbare Chromium-Datei",
            'archive_pdf_width' => 'Seitenbreite',
            'archive_pdf_height' => 'Seitenhöhe',
            'link_archive_media' => "Archvierung der Medien (Youtube, Soundcloud, ...)",
            'youtube_dl_bin' => "ausführbare Youtube-dl-Datei",
            'python_bin' => 'ausführbare Python-Datei',
            'check_pdf_archiving' => 'Testen Sie die PDF-Archivierung',
            'check_media_archiving' => 'Testen Sie die Medienarchivierung',
        ],

        'backup' => [
            'title' => 'Backup',
            'enabled' => 'Backup aktiviert?',
            'enabled_help' => 'Stellen Sie sicher, dass Ihre Sicherungskonfiguration korrekt ist.',
            'only_database' => 'Nur Datenbank speichern?',
            'period' => 'Sicherungszeitraum',
            'period_daily' => 'Täglich',
            'period_weekly' => 'Wöchentlich',
        ],

        'images' => [
            'title' => 'Bilder',
            'images_original_resize' => 'Originalbildgröße ändern?',
            'images_original_resize_width' => 'Ursprüngliche maximale Breite in Pixel',
            'images_thumb_format' => 'Thumbnail-Format',
            'format_square' => 'Platz',
            'format_original' => 'Original',
            'images_thumb_queue' => 'Vorschaubild im Hintergrund erzeugen?',
        ],

        'comments' => [
            'title' => 'Bemerkungen',
            'comments_enabled' => 'Kommentare aktivieren',
            'comments_guest_view' => 'Gäste können Kommentare sehen',
            'comments_guest_add' => 'Gäste können Kommentare hinzufügen',
            'comments_moderation' => 'Neue Kommentarmoderation',
            'comments_notification' => 'Benachrichtigung über neue Kommentare',
            'disabled' => 'Behindert',
            'whitelist' => 'Whitelisting',
            'all' => 'Alle',
        ],

        'links' => [
            'title' => 'Gesundheitsprüfungen verknüpfen',
            'health_checks_enabled' => 'Aktivieren Sie die Integritätsprüfungen',
            'health_checks_age' => 'Anzahl der Tage zwischen den Überprüfungen für jeden Link',
        ],
    ],

    // Mails
    'mails' => [
        '2fa' => [
            'title' => 'Sichern Sie Ihre Anmeldung ab',
            'message' => 'Bitte verwenden Sie den folgenden Code :code, um auf Ihr Konto zuzugreifen.',
            'button' => 'Anmeldung bestätigen'
        ],

        'check' => [
            'title' => 'Es klappt!',
            'message' => 'Diese E-Mail wurde von :name gesendet, um Ihre E-Mail-Konfiguration zu testen.',
        ],

        'comment' => [
            'title' => 'Neuer Kommentar',
            'message' => 'Sie haben einen Kommentar von ":name" (:email) zum Beitrag ":post".',
            'action' => 'Aussicht',
        ],

        'unmoderated' => [
            'title' => 'Neuer nicht moderierter Kommentar',
            'message' => 'Sie haben einen neuen nicht moderierten Kommentar von ":name" (:email) zum Post ":post".',
            'action' => 'Anschauen und moderieren',
        ]
    ],
];
