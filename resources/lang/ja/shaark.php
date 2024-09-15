<?php

return [
    // Settings
    'settings' => [
        'general' => [
            'title' => '一般',
            'install_button' => 'インストール',
            'site_name' => 'サイトの名前',
            'lang' => '言語',
            'private_help' => '非公開（全てのコンテンツが非公開になる。コンテンツへのアクセスにログインが必要。）',
            'private_download' => 'ダウンロードを非公開にしますか？（ＵＲＬとアルバム）',
            'use_default_search' => 'クラシックＳＱＬ検索を使用（デフォルト：全文検索）',
            'posts_order' => '優先投稿順',
            'created' => '作成日',
            'updated' => '最終更新日',
            'additional_js' => 'カスタムJS',
            'additional_css' => 'カスタムCSS',
        ],

        'appearance' => [
            'title' => '外見',
            'is_dark' => 'ダークモード',
            'custom_background' => 'カスタム背景',
            'custom_icon' => 'カスタムアイコン(PNG, 512px)',
        ],

        '2fa' => [
            'title' => '安全なログイン',
            'secure_login' => '二要素認証 (メールで送られた厳密コードは必要とする)',
            'secure_code_expires' => '厳密コードの有効期限 (分単位)',
            'secure_code_length' => '厳密コードの長さ',
        ],

        'archiving' => [
            'title' => 'アーカイブ中',
            'link_archive_pdf' => 'ＰＤＦアーカイブ (サイトをＰＤＦ化)',
            'node_bin' => 'Node.jsバイナリ',
            'chromium_bin' => "Chromium バイナリ",
            'archive_pdf_width' => 'ページ幅',
            'archive_pdf_height' => 'ページの高さ',
            'link_archive_media' => 'メディアアーカイブ (Youtube, Soundcloud, ...)',
            'youtube_dl_bin' => 'Youtube-dlバイナリ',
            'python_bin' => 'Pythonバイナリ',
            'check_pdf_archiving' => 'ＰＤＦアーカイブのテストを実行',
            'check_media_archiving' => 'メディアアーカイブのテストを実行',
        ],

        'backup' => [
            'title' => 'バックアップ',
            'enabled' => 'バックアップを有効にしますか？',
            'enabled_help' => 'バックアップの構成が正しいことを確認してください。',
            'only_database' => 'データーベースのみ',
            'period' => 'バックアップ頻度',
            'period_daily' => '毎日',
            'period_weekly' => '毎週',
        ],

        'images' => [
            'title' => '画像',
            'images_original_resize' => '元の画像のサイズを変更しますか？',
            'images_original_resize_width' => '元の画像の最大幅（ピクセル）',
            'images_thumb_format' => 'サムネールのフォーマット',
            'format_square' => '四角',
            'format_original' => 'オリジナル',
            'images_thumb_queue' => 'バックグラウンドでサムネールを生成しますか？',
        ],

        'comments' => [
            'title' => 'コメント',
            'comments_enabled' => 'コメントを有効にしますか？',
            'comments_guest_view' => 'ゲストはコメントを見ることができます',
            'comments_guest_add' => 'ゲストはコメントを追加できます',
            'comments_moderation' => '新しいコメントの管理',
            'comments_notification' => '新しいコメント通知',
            'disabled' => '無効',
            'whitelist' => 'ホワイトリスト',
            'all' => 'すべて',
        ],

        'links' => [
            'title' => 'ＵＲＬの健康チェック',
            'health_checks_enabled' => '健康チェックを有効にしますか？',
            'health_checks_age' => '各ＵＲＬの次のチェックを行うまで何日待ちますか？',
        ],
    ],

    // Mails
    'mails' => [
        '2fa' => [
            'title' => 'ログインを安全化にする',
            'message' => 'アカウントをアクセスするために、このコードを入力してください：:code',
            'button' => 'ログインを確認'
        ],

        'check' => [
            'title' => 'できます！',
            'message' => 'このメールは、メール設定をテストするために:nameによって送信されました。',
        ],

        'comment' => [
            'title' => '新しいコメント',
            'message' => '「:name」（:email）から投稿「:post」へのコメントがあります。',
            'action' => '表示する',
        ],

        'unmoderated' => [
            'title' => '新しいモデレートされていないコメント',
            'message' => '「:name」（:email）から投稿「:post」への新しいモデレートされていないコメントがあります。',
            'action' => '表示およびモデレート',
        ]
    ],
];
