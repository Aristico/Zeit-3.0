@extends('layouts.time')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Bestätigen Sie Ihre E-Mail-Adresse') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Eine neue Bestätgungsnachricht wurde an Ihre E-Mail-Adresse gesendet.') }}
                        </div>
                    @endif

                    {{ __('Bitte bestätigen Sie Ihre E-Mail-Adresse über den Bestätigungslink in unserer E-Mail.') }}
                    {{ __('Wenn Sie keine E-Mail erhalten haben') }}, <a href="{{ route('verification.resend') }}">{{ __('klicken Sie bitte hier um eine neue zu erhalten.') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
