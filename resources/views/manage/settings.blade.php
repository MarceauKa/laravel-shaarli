@extends('layouts.manage')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <form method="POST" action="{{ route('manage.settings') }}" enctype="multipart/form-data">
            @csrf

            <div class="card" id="general">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('shaark.settings.general.title') }}
                    <button type="button" class="btn btn-sm btn-outline-primary" @click="installPwa" :disabled="prompt === null">{{ __('shaark.settings.general.install_button') }}</button>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">{{ __('shaark.settings.general.site_name') }}</label>
                        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                               name="name" id="name" value="{{ old('name', $settings['name']) }}">
                        @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="locale">{{ __('shaark.settings.general.lang') }}</label>
                        <select name="locale" id="locale" class="form-control custom-select">
                            <option value="fr"{{ old('locale', $settings['locale']) == 'fr' ? ' selected' : '' }}>FR</option>
                            <option value="en"{{ old('locale', $settings['locale']) == 'en' ? ' selected' : '' }}>EN</option>
                            <option value="de"{{ old('locale', $settings['locale']) == 'de' ? ' selected' : '' }}>DE</option>
                            <option value="ja"{{ old('locale', $settings['locale']) == 'ja' ? ' selected' : '' }}>JA</option>
                            <option value="nl"{{ old('locale', $settings['locale']) == 'nl' ? ' selected' : '' }}>NL</option>
                        </select>
                        @error('locale')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="is_private" id="is_private" {{ old('is_private', $settings['is_private']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="is_private">{{ __('shaark.settings.general.private_help') }}</label>
                        </div>
                        @error('is_private')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="private_download" id="private_download" {{ old('private_download', $settings['private_download']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="private_download">{{ __('shaark.settings.general.private_download') }}</label>
                        </div>
                        @error('private_download')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="use_default_search" id="use_default_search" {{ old('use_default_search', $settings['use_default_search']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="use_default_search">{{ __('shaark.settings.general.use_default_search') }}</label>
                        </div>
                        @error('use_default_search')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="posts_order">{{ __('shaark.settings.general.posts_order') }}</label>
                        <select name="posts_order" id="posts_order" class="form-control custom-select">
                            <option value="created"{{ old('posts_order', $settings['posts_order']) == 'created' ? ' selected' : '' }}>{{ __('shaark.settings.general.created') }}</option>
                            <option value="updated"{{ old('posts_order', $settings['posts_order']) == 'updated' ? ' selected' : '' }}>{{ __('shaark.settings.general.updated') }}</option>
                        </select>
                        @error('posts_order')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="additional_js">{{ __('shaark.settings.general.additional_js') }}</label>
                        <textarea name="additional_js" id="additional_js" rows="4"
                                  class="form-control" placeholder="&lt;script&gt;...&lt;/script&gt;, &lt;script src='...'&gt;&lt;/script&gt;"
                        >{{ old('additional_js', $settings['additional_js']) }}</textarea>
                        @error('additional_js')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="additional_css">{{ __('shaark.settings.general.additional_css') }}</label>
                        <textarea name="additional_css" id="additional_css" rows="4"
                                  class="form-control" placeholder="&lt;link href='' rel='stylesheet' /&gt;, &lt;style&gt;&lt;/style&gt;"
                        >{{ old('additional_css', $settings['additional_css']) }}</textarea>
                        @error('additional_css')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-4" id="appearance">
                <div class="card-header">{{ __('shaark.settings.appearance.title') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="is_dark" id="is_dark" {{ old('is_dark', $settings['is_dark']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="is_dark">{{ __('shaark.settings.appearance.is_dark') }}</label>
                        </div>
                    </div>

                    <label for="name">{{ __('shaark.settings.appearance.custom_background') }}</label>
                    <background name="custom_background" :values="{{ $settings['custom_background'] ?? '{type: "none"}' }}"></background>
                    @error('custom_background')
                    <span class="text-danger" role="alert">{{ $message }}</span>
                    @enderror

                    <label for="name">{{ __('shaark.settings.appearance.custom_icon') }}</label>
                    <div class="custom-file">
                        <label for="custom_icon" class="custom-file-label" data-browse="{{ __('Browse') }}">{{ __('File') }}</label>
                        <input type="file" class="custom-file-input" name="custom_icon" id="custom_icon" accept="image/png" />
                    </div>
                    @error('custom_icon')
                    <span class="text-danger" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="card mt-4" id="2fa">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    {{ __('shaark.settings.2fa.title') }}
                    <span>
                        <check-feature type="email" class="btn btn-sm btn-outline-secondary" text="{{ __('shaark.settings.2fa.check_email') }}"></check-feature>
                    </span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="secure_login" id="secure_login" {{ old('secure_login', $settings['secure_login']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="secure_login">{{ __('shaark.settings.2fa.secure_login') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">{{ __('shaark.settings.2fa.secure_code_expires') }}</label>
                        <input type="number" class="form-control {{ $errors->has('secure_code_expires') ? ' is-invalid' : '' }}" step="5"
                               name="secure_code_expires" id="secure_code_expires" value="{{ old('secure_code_expires', $settings['secure_code_expires']) }}">
                        @error('secure_code_expires')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">{{ __('shaark.settings.2fa.secure_code_length') }}</label>
                        <input type="number" class="form-control {{ $errors->has('secure_code_length') ? ' is-invalid' : '' }}" step="1"
                               name="secure_code_length" id="secure_code_length" value="{{ old('secure_code_length', $settings['secure_code_length']) }}">

                        @error('secure_code_length')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-4" id="archiving">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    {{ __('shaark.settings.archiving.title') }}
                    <span>
                        <check-feature type="pdf" class="btn btn-sm btn-outline-secondary" text="{{ __('shaark.settings.archiving.check_pdf_archiving') }}"></check-feature>
                        <check-feature type="media" class="btn btn-sm btn-outline-secondary" text="{{ __('shaark.settings.archiving.check_media_archiving') }}"></check-feature>
                        <a href="{{ route('manage.archives') }}" class="btn btn-sm btn-outline-primary" >{{ __('Manage') }}</a>
                    </span>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="link_archive_pdf" id="link_archive_pdf" {{ old('link_archive_pdf', $settings['link_archive_pdf']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="link_archive_pdf">{{ __('shaark.settings.archiving.link_archive_pdf') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="node_bin">{{ __('shaark.settings.archiving.node_bin') }}</label>
                        <input type="text" class="form-control {{ $errors->has('node_bin') ? ' is-invalid' : '' }}"
                               name="node_bin" id="node_bin" value="{{ old('node_bin', $settings['node_bin']) }}">
                        @error('node_bin')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="chromium_bin">{{ __('shaark.settings.archiving.chromium_bin') }}</label>
                        <input type="text" class="form-control {{ $errors->has('chromium_bin') ? ' is-invalid' : '' }}"
                               name="chromium_bin" id="chromium_bin" value="{{ old('chromium_bin', $settings['chromium_bin']) }}">
                        @error('chromium_bin')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="archive_pdf_width">{{ __('shaark.settings.archiving.archive_pdf_width') }}</label>
                                <input type="number" class="form-control {{ $errors->has('archive_pdf_width') ? ' is-invalid' : '' }}" step="1" min="100" max="3840"
                                       name="archive_pdf_width" id="archive_pdf_width" value="{{ old('archive_pdf_width', $settings['archive_pdf_width']) }}">
                                @error('archive_pdf_width')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="archive_pdf_height">{{ __('shaark.settings.archiving.archive_pdf_height') }}</label>
                                <input type="number" class="form-control {{ $errors->has('archive_pdf_height') ? ' is-invalid' : '' }}" step="1" min="100" max="2160"
                                       name="archive_pdf_height" id="archive_pdf_height" value="{{ old('archive_pdf_height', $settings['archive_pdf_height']) }}">
                                @error('archive_pdf_height')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="link_archive_media" id="link_archive_media" {{ old('link_archive_media', $settings['link_archive_media']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="link_archive_media">{{ __('shaark.settings.archiving.link_archive_media') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="youtube_dl_bin">{{ __('shaark.settings.archiving.youtube_dl_bin') }}</label>
                        <input type="text" class="form-control {{ $errors->has('youtube_dl_bin') ? ' is-invalid' : '' }}"
                               name="youtube_dl_bin" id="youtube_dl_bin" value="{{ old('youtube_dl_bin', $settings['youtube_dl_bin']) }}">
                        @error('youtube_dl_bin')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="python_bin">{{ __('shaark.settings.archiving.python_bin') }}</label>
                        <input type="text" class="form-control {{ $errors->has('python_bin') ? ' is-invalid' : '' }}"
                               name="python_bin" id="python_bin" value="{{ old('python_bin', $settings['python_bin']) }}">
                        @error('python_bin')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-4" id="backup">
                <div class="card-header">{{ __('shaark.settings.backup.title') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="backup_enabled" id="backup_enabled" {{ old('backup_enabled', $settings['backup_enabled']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="backup_enabled">{{ __('shaark.settings.backup.enabled') }}</label>
                        </div>
                        <span class="text-muted">{{ __('shaark.settings.backup.enabled_help') }}</span>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="backup_only_database" id="backup_only_database" {{ old('backup_only_database', $settings['backup_only_database']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="backup_only_database">{{ __('shaark.settings.backup.only_database') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="backup_period">{{ __('shaark.settings.backup.period') }}</label>
                        <select name="backup_period" id="backup_period" class="form-control custom-select">
                            <option value="daily"{{ old('backup_period', $settings['backup_period']) == 'daily' ? ' selected' : '' }}>{{ __('shaark.settings.backup.period_daily') }}</option>
                            <option value="weekly"{{ old('backup_period', $settings['backup_period']) == 'weekly' ? ' selected' : '' }}>{{ __('shaark.settings.backup.period_weekly') }}</option>
                        </select>
                        @error('backup_period')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-4" id="images">
                <div class="card-header">{{ __('shaark.settings.images.title') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="images_original_resize" id="images_original_resize" {{ old('images_original_resize', $settings['images_original_resize']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="images_original_resize">{{ __('shaark.settings.images.images_original_resize') }}</label>
                        </div>
                        @error('images_original_resize')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">{{ __('shaark.settings.images.images_original_resize_width') }}</label>
                        <input type="number" class="form-control {{ $errors->has('images_original_resize_width') ? ' is-invalid' : '' }}" step="100" min="500" max="5000"
                               name="images_original_resize_width" id="images_original_resize_width" value="{{ old('images_original_resize_width', $settings['images_original_resize_width']) }}">
                        @error('images_original_resize_width')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="images_thumb_format">{{ __('shaark.settings.images.images_thumb_format') }}</label>
                        <select name="images_thumb_format" id="images_thumb_format" class="form-control custom-select">
                            <option value="square"{{ old('images_thumb_format', $settings['images_thumb_format']) == 'square' ? ' selected' : '' }}>{{ __('shaark.settings.images.format_square') }}</option>
                            <option value="original"{{ old('images_thumb_format', $settings['images_thumb_format']) == 'original' ? ' selected' : '' }}>{{ __('shaark.settings.images.format_original') }}</option>
                        </select>
                        @error('images_thumb_format')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="images_thumb_queue"
                                   id="images_thumb_queue" {{ old('images_thumb_queue', $settings['images_thumb_queue']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="images_thumb_queue">{{ __('shaark.settings.images.images_thumb_queue') }}</label>
                        </div>
                        @error('images_thumb_queue')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-4" id="comments">
                <div class="card-header">{{ __('shaark.settings.comments.title') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="comments_enabled" id="comments_enabled" {{ old('comments_enabled', $settings['comments_enabled']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="comments_enabled">{{ __('shaark.settings.comments.comments_enabled') }}</label>
                        </div>
                        @error('comments_enabled')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="comments_guest_view" id="comments_guest_view" {{ old('comments_guest_view', $settings['comments_guest_view']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="comments_guest_view">{{ __('shaark.settings.comments.comments_guest_view') }}</label>
                        </div>
                        @error('comments_guest_view')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="comments_guest_add" id="comments_guest_add" {{ old('comments_guest_add', $settings['comments_guest_add']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="comments_guest_add">{{ __('shaark.settings.comments.comments_guest_add') }}</label>
                        </div>
                        @error('comments_guest_add')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="comments_moderation">{{ __('shaark.settings.comments.comments_moderation') }}</label>
                        <select name="comments_moderation" id="comments_moderation" class="form-control custom-select">
                            <option value="disabled"{{ old('comments_moderation', $settings['comments_moderation']) == 'disabled' ? ' selected' : '' }}>{{ __('shaark.settings.comments.disabled') }}</option>
                            <option value="whitelist"{{ old('comments_moderation', $settings['comments_moderation']) == 'whitelist' ? ' selected' : '' }}>{{ __('shaark.settings.comments.whitelist') }}</option>
                            <option value="all"{{ old('comments_moderation', $settings['comments_moderation']) == 'all' ? ' selected' : '' }}>{{ __('shaark.settings.comments.all') }}</option>
                        </select>
                        @error('comments_moderation')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="comments_notification">{{ __('shaark.settings.comments.comments_notification') }}</label>
                        <select name="comments_notification" id="comments_notification" class="form-control custom-select">
                            <option value="disabled"{{ old('comments_notification', $settings['comments_notification']) == 'disabled' ? ' selected' : '' }}>{{ __('shaark.settings.comments.disabled') }}</option>
                            <option value="whitelist"{{ old('comments_notification', $settings['comments_notification']) == 'whitelist' ? ' selected' : '' }}>{{ __('shaark.settings.comments.whitelist') }}</option>
                            <option value="all"{{ old('comments_notification', $settings['comments_notification']) == 'all' ? ' selected' : '' }}>{{ __('shaark.settings.comments.all') }}</option>
                        </select>
                        @error('comments_notification')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-4" id="links">
                <div class="card-header">{{ __('shaark.settings.links.title') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input"
                                   name="link_health_checks_enabled" id="link_health_checks_enabled" {{ old('link_health_checks_enabled', $settings['link_health_checks_enabled']) ? ' checked' : '' }}>
                            <label class="custom-control-label" for="link_health_checks_enabled">{{ __('shaark.settings.links.health_checks_enabled') }}</label>
                        </div>
                        @error('link_health_checks_enabled')
                        <span class="text-danger" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('shaark.settings.links.health_checks_age') }}</label>
                        <input type="number" class="form-control {{ $errors->has('link_health_checks_age') ? ' is-invalid' : '' }}" step="1" min="7" max="365"
                               name="link_health_checks_age" id="link_health_checks_age" value="{{ old('link_health_checks_age', $settings['link_health_checks_age']) }}">
                        @error('link_health_checks_age')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <button type="reset" class="btn btn-secondary">{{ __('Reset') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
