@extends('admin::layouts.main')

@section('body')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-5">
                <h1 class="text-center mb-4">{{ __('Admin Panel') }}</h1>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.login') }}" method="post">
                            @csrf

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user-shield"></i></span>
                                </div>
                                <input class="form-control @error('username') is-invalid @enderror" name="username"
                                       placeholder="{{ __('Username') }}" type="text" value="{{ old('username') }}"
                                       required autocomplete="username" autofocus>
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-fingerprint"></i></span>
                                </div>
                                <input class="form-control @error('password') is-invalid @enderror" name="password"
                                       placeholder="{{ __('Password') }}" required type="password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="custom-control custom-checkbox mb-4">
                                <input class="custom-control-input" id="remember" name="remember"
                                       type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <div class="row">
                                <div class="col pr-2">
                                    <button type="submit" class="btn btn-block btn-primary">{{ __('Login') }}</button>
                                </div>
                                <div class="col pl-2">
                                    <a class="btn btn-block btn-link" href="{{ route('admin.password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
