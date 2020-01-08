@extends('admin::layouts.main')

@section('body')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        <form action="{{ route('admin.password.update') }}" method="post">
                            @csrf

                            <input name="token" type="hidden" value="{{ $token }}">

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right" for="email">
                                    {{ __('E-Mail Address') }}
                                </label>

                                <div class="col-md-6">
                                    <input class="form-control @error('email') is-invalid @enderror" id="email"
                                           name="email" type="email" value="{{ $email ?? old('email') }}" required
                                           autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right" for="password">
                                    {{ __('Password') }}
                                </label>

                                <div class="col-md-6">
                                    <input class="form-control @error('password') is-invalid @enderror" id="password"
                                           name="password" type="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right" for="password-confirm">
                                    {{ __('Confirm Password') }}
                                </label>

                                <div class="col-md-6">
                                    <input class="form-control" id="password-confirm" name="password_confirmation"
                                           type="password" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
