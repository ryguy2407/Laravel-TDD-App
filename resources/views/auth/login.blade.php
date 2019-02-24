@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py-12 md:px-16 rounded shadow">
            <h1 class="text-2xl font-normal mb-10 text-center">
                {{ __('Login') }}
            </h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field mb-6">
                    <input id="email" type="email" class="input bg-transparent border border-grey-light rounded p-2 text-xs w-full" name="email" value="" placeholder="Email Address" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                </div>

                <div class="field mb-6">
                    <input id="password" type="password" class="input bg-transparent border border-grey-light rounded p-2 text-xs w-full" name="password" placeholder="Password" required>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group row">
                    <div class="mb-5">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-link mr-2">Login</button>
                    </div>
                </div>

                <div class="form-group row mt-5">
                    <div class="col-md-8 offset-md-4">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
