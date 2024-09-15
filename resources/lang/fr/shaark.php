<?php

return [
    // Settings
    'settings' => [
        'general' => [
            'title' => 'Général',
            'install_button' => 'Installer',
            'site_name' => 'Nom du site',
            'lang' => 'Langue',
            'private_help' => "Contenu privé (tout est privé et nécessite d'être connecté)",
            'private_download' => 'Garder les téléchargements privés (liens et albums)',
            'use_default_search' => 'Utiliser la recherche SQL plutôt que la recherche full-text',
            'posts_order' => 'Ordre des posts',
            'created' => 'Date de création',
            'updated' => 'Date de dernière modification',
            'additional_js' => 'Javascript personnalisé',
            'additional_css' => 'CSS personnalisé',
        ],

        'appearance' => [
            'title' => 'Apparence',
            'is_dark' => 'Mode sombre',
            'custom_background' => "Fond d'écran personnalisé",
            'custom_icon' => "Icône personnalisé (PNG, 512px)",
        ],

        '2fa' => [
            'title' => "Connexion sécurisée",
            'secure_login' => "Activer 2-FA (requiert un code envoyé par email)",
            'secure_code_expires' => "Durée d'expiration du code de sécurité (en minutes)",
            'secure_code_length' => "Taille du code de sécurité",
        ],

        'archiving' => [
            'title' => "Archivage",
            'link_archive_pdf' => "Archiver les pages en PDF",
            'node_bin' => "Éxécutable node.js",
            'chromium_bin' => "Éxécutable Chromium",
            'archive_pdf_width' => 'Largeur du PDF',
            'archive_pdf_height' => 'Hauteur du PDF',
            'link_archive_media' => "Archiver les médias (Youtube, Soundcloud, ...)",
            'youtube_dl_bin' => "Éxécutable Youtube-dl",
            'python_bin' => 'Éxécutable Python',
            'check_pdf_archiving' => 'Tester archive PDF',
            'check_media_archiving' => 'Tester archive Média',
        ],

        'backup' => [
            'title' => 'Sauvegarde',
            'enabled' => 'Sauvegarde activée ?',
            'enabled_help' => "Vérifiez votre configuration avant de l'activer.",
            'only_database' => 'Sauvegarder seulement la base de données',
            'period' => 'Période de sauvegarde',
            'period_daily' => 'Tous les jours',
            'period_weekly' => 'Toutes les semaines',
        ],

        'images' => [
            'title' => 'Images',
            'images_original_resize' => "Redimensionner l'image originale",
            'images_original_resize_width' => "Largeur max de l'image originale en pixels",
            'images_thumb_format' => 'Format de la miniature',
            'format_square' => 'Carré',
            'format_original' => 'Original',
            'images_thumb_queue' => 'Générer les miniatures en tâche de fond ?',
        ],

        'comments' => [
            'title' => 'Commentaires',
            'comments_enabled' => 'Commentaires activés ?',
            'comments_guest_view' => 'Les invités peuvent voir les commentaires',
            'comments_guest_add' => 'Les invités peuvent ajouter des commentaires',
            'comments_moderation' => 'Modération des nouveaux commentaires',
            'comments_notification' => 'Notifications des nouveaux commentaires',
            'disabled' => 'Désactivé',
            'whitelist' => 'Liste blanche',
            'all' => 'Tous',
        ],

        'links' => [
            'title' => 'Surveillance de la santé des liens',
            'health_checks_enabled' => 'Activer la surveillance',
            'health_checks_age' => 'Nombre de jour entre chaque vérification',
        ],
    ],

    // Mails
    'mails' => [
        '2fa' => [
            'title' => 'Sécurisez votre connexion',
            'message' => 'Veuillez utiliser le code suivant :code pour sécuriser votre connexion.',
            'button' => 'Confirmer la connexion'
        ],

        'check' => [
            'title' => 'Tout fonctionne !',
            'message' => 'Cet email a été envoyé par :name pour tester la configuration email.',
        ],

        'comment' => [
            'title' => 'Nouveau commentaire',
            'message' => 'Vous avez un nouveau commentaire de ":name" (:email) sur le post ":post".',
            'action' => 'Voir',
        ],

        'unmoderated' => [
            'title' => 'Nouveau commentaire à modérer',
            'message' => 'Vous avez un nouveau commentaire à modérer de ":name" (:email) sur le post ":post".',
            'action' => 'Voir et modérer',
        ]
    ],
];
