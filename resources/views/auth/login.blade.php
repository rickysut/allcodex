@extends('layouts.app')
@section('content')

    
<div class="row justify-content-center my-auto">
    <div class="col-md-6">
        <div class="card mx-4">
            <div class="card-body p-4 text-center">
                <h1>{{ trans('panel.site_title') }}</h1>

                {{-- <p class="text-muted">{{ trans('global.login') }}</p> --}}
                @if (session('status'))
                    <div class="alert alert-danger">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-error" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('message'))
                    <div class="alert alert-info" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>

                        <input id="email" name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">

                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        </div>

                        <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}" autocomplete="current-password">

                        @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="input-group mb-4">
                        <div class="form-check checkbox">
                            <input class="form-check-input" name="remember" type="checkbox" id="remember" style="vertical-align: middle;" />
                            <label class="form-check-label" for="remember" style="vertical-align: middle;">
                                {{ trans('global.remember_me') }}
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block px-4">
                                {{ trans('global.login') }}
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 text-center text-muted">
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-muted text-decoration-underline">{{ trans('global.forgot_password') }}</a>
                        @endif
                    </div>
                </form>
                
                <div class="mt-3 text-center text-muted">
                    <p>Don't have an account ? <a href="{{ route('register') }}"
                            class="fw-medium text-decoration-underline"> {{ trans('global.register') }} </a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center text-muted p-4">
                <p class="mb-0">&copy;
                    <script>
                        document.write(new Date().getFullYear())
                        </script> All Rights Reserved <i class="fa fa-heart text-danger"></i> Allcodex</p>
            </div>
        </div>
    </div>
</div>
        

@endsection