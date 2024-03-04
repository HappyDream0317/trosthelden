@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('E-Mail Bestätigung') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Eine neue E-Mail zur Bestätigung deiner Adresse wurde versendet.') }}
                        </div>
                    @endif

                    {{ __('Bevor du fortfährst, prüfe bitte deine E-Mail Adresse..') }}
                    {{ __('Wenn du keine E-Mail erhalten hast') }}, <a href="{{ route('verification.resend') }}">{{ __('klicke hier, um eine neue E-Mail zu versenden') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
