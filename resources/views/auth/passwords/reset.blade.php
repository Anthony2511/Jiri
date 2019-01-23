@extends('layouts.app')
@section('custom-class', 'login-page')
@section('content')

    <section class="wrapper-login">
        <div class="wrapper-login-form">
            <h1 class="login__title" role="heading" aria-level="1">Bienvenue sur Jiri</h1>
            <span class="login__subtitle">La web app de la section web</span>

            <div class="login-form">
                <div class="login-form__icon-container">
                    <figure class="login-form__icon">
                        <img src="{{ asset('img/login-icon.svg') }}" width="57" height="49" alt="">
                     </figure>
                </div>
                <el-form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <el-form-item label="Adresse e-mail" for="email" required>
                        <el-input type="email" id="email" name="email" value="{{ old('email') }}" autofocus></el-input>
                    </el-form-item>

                    <!-- Password -->
                    <el-form-item label="Nouveau mot de passe" for="password" required>
                        <el-input type="password" id="password" name="password" value="{{ old('password') }}" autofocus></el-input>
                    </el-form-item>

                    <!-- Password confirm -->
                    <el-form-item label="Répeter le nouveau mot de passe" for="password-confirm" required>
                        <el-input type="password" id="password-confirm" name="password_confirmation" value="{{ old('password_confirmation') }}" autofocus></el-input>
                    </el-form-item>

                    <button type="submit" class="el-button el-button--primary" style="width: auto;">
                        <span>Générer un nouveau mot de passe</span>
                    </button>

                    <span class="errors">
                        {{ $errors->first() }}
                    </span>

                </el-form>
             </div>

        </div>
    </section>

@endsection
