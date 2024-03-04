@php
    /**
    * @var $user App\User
    */
@endphp

@component('mail::message')
    <p><b>"fristgerechte Kündigung"</b></p>
    <p>username: {{ $user->nickname }}</p>
    <p>id: {{ $user->id }}</p>
    <p>reason: {{ $reason }}</p>
@endcomponent
