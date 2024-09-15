@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body p-0">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'general' ? 'active' : '' }}" href="{{ route('account') }}">{{ __('Update account') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'password' ? 'active' : '' }}" href="{{ route('account.password') }}">{{ __('Update password') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'logins' ? 'active' : '' }}" href="{{ route('account.logins') }}">{{ __('Logins') }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            @if($tab === 'general')
            <div class="tab-content">
                <div class="tab-pane show active">
                    <div class="card">
                        <div class="card-header">{{ __("Update account") }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('account') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input type="text" name="name" id="name"
                                                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                   value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">{{ __('Email') }}</label>
                                            <input type="email" name="email" id="email"
                                                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                   value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($tab === 'password')
            <div class="tab-pane show active">
                <div class="card">
                    <div class="card-header">{{ __("Update password") }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('account.password') }}#password" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_password">{{ __('New password') }}</label>
                                        <input type="password" class="form-control {{ $errors->has('new_password') ? 'is-invalid' : '' }}"
                                               name="new_password" id="new_password">
                                        @error('new_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_password_confirmation">{{ __('Confirm password') }}</label>
                                        <input type="password" class="form-control"
                                               name="new_password_confirmation" id="new_password_confirmation">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="current_password">{{ __('Current password') }}</label>
                                <input type="password" class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                       name="current_password" id="current_password" autocomplete="new-password">
                                @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            @if($tab === 'logins')
            <div class="tab-pane show active">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ __('Logins') }}
                        @if($logins->isNotEmpty())
                            <purge-logins class="btn btn-danger btn-sm text-white" endpoint="{{ route('api.account.logins.purge') }}"></purge-logins>
                        @endif
                    </div>

                    <div class="card-body">
                        @if($logins->isEmpty())
                            <div class="alert alert-info">{{ __('No logins') }}</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th>{{ __('IP address') }}</th>
                                        <th>{{ __('System') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($logins as $login)
                                        <tr>
                                            <td class="align-middle">
                                                <a href="https://www.ip-tracker.org/locator/ip-lookup.php?ip={{ $login->ip_address }}" title="TraceIP" target="_blank">
                                                    {{ $login->ip_address }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-2">
                                                        @if($login->device->is_mobile)
                                                        <i class="fas fa-mobile-alt"></i>
                                                        @else
                                                        <i class="fas fa-laptop"></i>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <div>{{ $login->device->platform }} <small>{{ $login->device->platform_version }}</small></div>
                                                        <div>{{ $login->device->browser }} <small>{{ $login->device->browser_version }}</small></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">{{ $login->created_at->diffForHumans() }}</td>
                                            <td class="align-middle">
                                                @if($login->type === App\Models\Login::TYPE_LOGIN)
                                                    <span class="badge badge-success">{{ __('Succeeded') }}</span>
                                                @elseif($login->type === App\Models\Login::TYPE_2FA)
                                                    <span class="badge badge-warning">{{ __('2FA') }}</span>
                                                @elseif($login->type === App\Models\Login::TYPE_LOCKOUT)
                                                    <span class="badge badge-danger">{{ __('Locked') }}</span>
                                                @elseif($login->type === App\Models\Login::TYPE_FAILED)
                                                    <span class="badge badge-danger">{{ __('Failed') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $logins->links() }}
                        @endif
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">{{ __('Logout from other devices') }}</div>

                    <div class="card-body">
                        <p>{{ __("Type your current password to logout from all other sessions.") }}</p>

                        <form action="{{ route('account.logins.logout') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required />
                                @error('password')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-outline-danger">{{ __('Confirm') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
