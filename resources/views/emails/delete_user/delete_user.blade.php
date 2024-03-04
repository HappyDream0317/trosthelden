@php
    /**
    * @var $title string
    * @var $novaUrl string
    * @var $user \App\User
    */
@endphp

@component('mail::message')
# {{ $title }}

@component('mail::button', ['url' => $novaUrl])
    Zum User in Nova
@endcomponent

{{ $user->id }}
{{ $user->nickname }}

@endcomponent
